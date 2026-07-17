<?php

namespace App\Services;

use App\Models\FaceEncoding;
use App\Models\PersonPhoto;
use App\Models\PhotoTag;
use App\Services\FacialRecognition\FacialRecognitionProviderInterface;
use App\Services\FacialRecognition\Providers\MockProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FacialRecognitionService
{
    protected ?FacialRecognitionProviderInterface $provider;

    public function __construct()
    {
        $this->provider = $this->getProvider();
    }

    /**
     * The configured provider, or null when none is available.
     *
     * MockProvider does not detect anything: it invents 1-3 faces at random bounding
     * boxes, assigns a person on a 60% coin flip with a 70-95% "confidence", and
     * returns random_bytes() as the face encoding. It was both the default and the
     * only implementation — aws/azure below have never existed — so every install
     * wrote fabricated PhotoTag and FaceEncoding rows and presented them for review
     * as detections. It is now opt-in, and anything else yields no provider at all
     * rather than silently falling back to it.
     */
    protected function getProvider(): ?FacialRecognitionProviderInterface
    {
        return match (config('services.facial_recognition.provider', 'none')) {
            'mock' => new MockProvider,
            // 'aws' => new AwsRekognitionProvider(),
            // 'azure' => new AzureFaceApiProvider(),
            default => null,
        };
    }

    /**
     * Whether face detection can run at all. isAvailable() was declared on the
     * provider interface but never called by anything — this is the gate it was for.
     */
    public function isAvailable(): bool
    {
        return $this->provider?->isAvailable() ?? false;
    }

    /**
     * Analyze a photo and create tags for detected faces
     *
     * @return array Results of the analysis
     */
    public function analyzePhoto(PersonPhoto $photo): array
    {
        if (! $this->isAvailable()) {
            Log::warning('No facial recognition provider configured; photo not analysed', [
                'photo_id' => $photo->id,
            ]);

            return [
                'success' => false,
                'error' => 'Facial recognition is not configured.',
            ];
        }

        try {
            $imagePath = Storage::disk('public')->path($photo->file_path);

            if (! file_exists($imagePath)) {
                Log::error('Photo file not found', ['path' => $imagePath]);

                return [
                    'success' => false,
                    'error' => 'Photo file not found',
                ];
            }

            // Detect faces in the photo
            $detectedFaces = $this->provider->detectFaces($imagePath);

            Log::info('Faces detected', [
                'photo_id' => $photo->id,
                'face_count' => count($detectedFaces),
            ]);

            if ($detectedFaces === []) {
                $photo->update([
                    'is_analyzed' => true,
                    'analyzed_at' => now(),
                ]);

                return [
                    'success' => true,
                    'faces_detected' => 0,
                    'tags_created' => 0,
                ];
            }

            // Get existing face encodings for matching
            $existingEncodings = $this->getExistingEncodings($photo->team_id);

            // Try to match detected faces with known people
            $matches = [];
            if ($existingEncodings !== []) {
                $matches = $this->provider->matchFaces($imagePath, $existingEncodings);
            }

            // Create tags for detected faces
            $tagsCreated = $this->createPhotoTags($photo, $detectedFaces, $matches);

            // Mark photo as analyzed
            $photo->update([
                'is_analyzed' => true,
                'analyzed_at' => now(),
            ]);

            return [
                'success' => true,
                'faces_detected' => count($detectedFaces),
                'tags_created' => $tagsCreated,
                'matches_found' => count($matches),
            ];
        } catch (\Exception $e) {
            Log::error('Error analyzing photo', [
                'photo_id' => $photo->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get existing face encodings for the team
     */
    protected function getExistingEncodings(?int $teamId): array
    {
        $query = FaceEncoding::with('person:id,givn,surn');

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->get()->map(fn ($encoding) => [
            'person_id' => $encoding->person_id,
            'person_name' => $encoding->person->fullname(),
            'encoding' => $encoding->encoding,
        ])->toArray();
    }

    /**
     * Create photo tags from detected faces and matches
     */
    protected function createPhotoTags(PersonPhoto $photo, array $detectedFaces, array $matches): int
    {
        $tagsCreated = 0;

        foreach ($detectedFaces as $index => $face) {
            // Find if this face matches a known person
            $matchedPerson = null;
            $confidence = $face['confidence'] ?? 0;

            foreach ($matches as $match) {
                if (($match['face_index'] ?? null) === $index) {
                    $matchedPerson = $match['person_id'];
                    $confidence = $match['confidence'];
                    break;
                }
            }

            // Create the photo tag
            PhotoTag::create([
                'photo_id' => $photo->id,
                'person_id' => $matchedPerson,
                'team_id' => $photo->team_id,
                'confidence' => $confidence,
                'bounding_box' => $face['bounding_box'],
                'status' => 'pending', // All tags start as pending for review
            ]);

            $tagsCreated++;
        }

        return $tagsCreated;
    }

    /**
     * Create a face encoding for a person from a confirmed tag
     */
    public function createFaceEncoding(PhotoTag $tag): ?FaceEncoding
    {
        if (! $tag->person_id || $tag->status !== 'confirmed') {
            return null;
        }

        // Without a provider there is nothing to encode. MockProvider returned
        // base64_encode(random_bytes(128)) here, so confirming a tag stored random
        // noise as that person's face signature — which any later real matching
        // would then compare against.
        if (! $this->isAvailable()) {
            Log::warning('No facial recognition provider configured; no encoding created', [
                'tag_id' => $tag->id,
            ]);

            return null;
        }

        try {
            $photo = $tag->photo;
            $imagePath = Storage::disk('public')->path($photo->file_path);

            if (! file_exists($imagePath)) {
                Log::error('Photo file not found for encoding', ['path' => $imagePath]);

                return null;
            }

            $encoding = $this->provider->getFaceEncoding($imagePath, $tag->bounding_box);

            return FaceEncoding::create([
                'person_id' => $tag->person_id,
                'team_id' => $tag->team_id,
                'source_photo_id' => $photo->id,
                'encoding' => $encoding,
                'provider' => config('services.facial_recognition.provider', 'mock'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating face encoding', [
                'tag_id' => $tag->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Confirm a photo tag and optionally create a face encoding
     */
    public function confirmTag(PhotoTag $tag, int $userId, bool $createEncoding = true): bool
    {
        $tag->confirm($userId);

        if ($createEncoding && $tag->person_id) {
            $this->createFaceEncoding($tag);
        }

        return true;
    }

    /**
     * Update a tag with a different person
     */
    public function updateTagPerson(PhotoTag $tag, int $personId, int $userId): bool
    {
        $tag->update([
            'person_id' => $personId,
            'status' => 'confirmed',
            'confirmed_by' => $userId,
            'confirmed_at' => now(),
        ]);

        $this->createFaceEncoding($tag);

        return true;
    }

    /**
     * Reject a photo tag
     */
    public function rejectTag(PhotoTag $tag): bool
    {
        $tag->reject();

        return true;
    }

    /**
     * Get pending tags for review
     *
     * @return Collection
     */
    public function getPendingTags(?int $teamId = null, int $limit = 50)
    {
        $query = PhotoTag::with(['photo', 'person'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->get();
    }
}
