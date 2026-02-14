<?php

namespace App\Services;

use App\Models\FaceEncoding;
use App\Models\PersonPhoto;
use App\Models\PhotoTag;
use App\Services\FacialRecognition\FacialRecognitionProviderInterface;
use App\Services\FacialRecognition\Providers\MockProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FacialRecognitionService
{
    protected FacialRecognitionProviderInterface $provider;

    public function __construct()
    {
        // Default to mock provider for development
        // Can be configured to use AWS Rekognition or other providers via config
        $this->provider = $this->getProvider();
    }

    /**
     * Get the configured facial recognition provider
     */
    protected function getProvider(): FacialRecognitionProviderInterface
    {
        $provider = config('services.facial_recognition.provider', 'mock');

        return match ($provider) {
            'mock' => new MockProvider(),
            // 'aws' => new AwsRekognitionProvider(),
            // 'azure' => new AzureFaceApiProvider(),
            default => new MockProvider(),
        };
    }

    /**
     * Analyze a photo and create tags for detected faces
     *
     * @param PersonPhoto $photo
     * @return array Results of the analysis
     */
    public function analyzePhoto(PersonPhoto $photo): array
    {
        try {
            $imagePath = Storage::disk('public')->path($photo->file_path);

            if (!file_exists($imagePath)) {
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

            if (empty($detectedFaces)) {
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
            if (!empty($existingEncodings)) {
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

        return $query->get()->map(function ($encoding) {
            return [
                'person_id' => $encoding->person_id,
                'person_name' => $encoding->person->fullname(),
                'encoding' => $encoding->encoding,
            ];
        })->toArray();
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
     *
     * @param PhotoTag $tag
     * @return FaceEncoding|null
     */
    public function createFaceEncoding(PhotoTag $tag): ?FaceEncoding
    {
        if (!$tag->person_id || $tag->status !== 'confirmed') {
            return null;
        }

        try {
            $photo = $tag->photo;
            $imagePath = Storage::disk('public')->path($photo->file_path);

            if (!file_exists($imagePath)) {
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
     *
     * @param PhotoTag $tag
     * @param int $userId
     * @param bool $createEncoding
     * @return bool
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
     *
     * @param PhotoTag $tag
     * @param int $personId
     * @param int $userId
     * @return bool
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
     *
     * @param PhotoTag $tag
     * @return bool
     */
    public function rejectTag(PhotoTag $tag): bool
    {
        $tag->reject();
        return true;
    }

    /**
     * Get pending tags for review
     *
     * @param int|null $teamId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
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
