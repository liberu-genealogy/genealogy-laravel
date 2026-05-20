<?php

namespace App\Services;

use App\Models\DocumentTranscription;
use App\Models\TranscriptionCorrection;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HandwritingRecognitionService
{
    /**
     * Process an uploaded document for transcription
     */
    public function processDocument(UploadedFile $file, User $user, int $teamId): DocumentTranscription
    {
        // Store the uploaded file
        $path = $file->store('transcriptions', 'public');

        // Create the transcription record
        $transcription = DocumentTranscription::create([
            'team_id' => $teamId,
            'user_id' => $user->id,
            'original_filename' => $file->getClientOriginalName(),
            'document_path' => $path,
            'status' => 'processing',
        ]);

        try {
            // Perform OCR using Google Cloud Vision API or fallback
            $result = $this->performOCR(Storage::disk('public')->path($path));

            // Update transcription with results
            $transcription->update([
                'raw_transcription' => $result['text'],
                'metadata' => [
                    'confidence' => $result['confidence'],
                    'language' => $result['language'] ?? 'en',
                    'processing_time' => $result['processing_time'] ?? null,
                ],
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            Log::info('Document transcription completed', [
                'transcription_id' => $transcription->id,
                'confidence' => $result['confidence'],
            ]);
        } catch (\Exception $e) {
            $transcription->update([
                'status' => 'failed',
                'metadata' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            Log::error('Document transcription failed', [
                'transcription_id' => $transcription->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $transcription;
    }

    /**
     * Perform OCR on an image file
     */
    protected function performOCR(string $filePath): array
    {
        $startTime = microtime(true);

        // Check if Google Cloud Vision API key is configured
        $apiKey = config('services.google_vision.api_key');

        if ($apiKey) {
            $result = $this->performGoogleVisionOCR($filePath, $apiKey);
        } else {
            // Fallback to basic OCR or simulated result
            $result = $this->performFallbackOCR($filePath);
        }

        $result['processing_time'] = round(microtime(true) - $startTime, 2);

        return $result;
    }

    /**
     * Perform OCR using Google Cloud Vision API
     */
    protected function performGoogleVisionOCR(string $filePath, string $apiKey): array
    {
        $imageContent = base64_encode(file_get_contents($filePath));

        $response = Http::post("https://vision.googleapis.com/v1/images:annotate?key={$apiKey}", [
            'requests' => [
                [
                    'image' => [
                        'content' => $imageContent,
                    ],
                    'features' => [
                        [
                            'type' => 'DOCUMENT_TEXT_DETECTION',
                        ],
                    ],
                ],
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Google Vision API request failed: ' . $response->body());
        }

        $data = $response->json();
        $textAnnotations = $data['responses'][0]['textAnnotations'] ?? [];

        if (empty($textAnnotations)) {
            return [
                'text' => '',
                'confidence' => 0,
                'language' => 'en',
            ];
        }

        // First annotation contains the full text
        $fullText = $textAnnotations[0]['description'] ?? '';

        // Calculate average confidence
        $confidenceScores = [];
        foreach ($textAnnotations as $annotation) {
            if (isset($annotation['confidence'])) {
                $confidenceScores[] = $annotation['confidence'];
            }
        }

        $avgConfidence = !empty($confidenceScores) 
            ? round(array_sum($confidenceScores) / count($confidenceScores), 2)
            : 0.85; // Default confidence if not provided

        // Detect language
        $language = $data['responses'][0]['fullTextAnnotation']['pages'][0]['property']['detectedLanguages'][0]['languageCode'] ?? 'en';

        return [
            'text' => $fullText,
            'confidence' => $avgConfidence,
            'language' => $language,
        ];
    }

    /**
     * Fallback OCR method (simulated for demonstration)
     * In production, you could integrate Tesseract OCR or another library
     */
    protected function performFallbackOCR(string $filePath): array
    {
        // This is a placeholder implementation
        // In a real application, you would use Tesseract or another OCR library
        return [
            'text' => "This is a placeholder transcription.\n\nTo enable real handwriting recognition, configure Google Cloud Vision API key in config/services.php:\n\n'google_vision' => [\n    'api_key' => env('GOOGLE_VISION_API_KEY'),\n],\n\nAnd set GOOGLE_VISION_API_KEY in your .env file.",
            'confidence' => 0.75,
            'language' => 'en',
        ];
    }

    /**
     * Apply user corrections to a transcription
     */
    public function applyCorrection(
        DocumentTranscription $transcription,
        User $user,
        string $correctedText,
        ?string $originalText = null,
        ?int $positionStart = null,
        ?int $positionEnd = null
    ): TranscriptionCorrection {
        // Create correction record
        $correction = TranscriptionCorrection::create([
            'document_transcription_id' => $transcription->id,
            'user_id' => $user->id,
            'original_text' => $originalText ?? $transcription->getCurrentTranscription(),
            'corrected_text' => $correctedText,
            'position_start' => $positionStart,
            'position_end' => $positionEnd,
            'correction_metadata' => [
                'timestamp' => now()->toIso8601String(),
                'original_confidence' => $transcription->getConfidenceScore(),
            ],
        ]);

        // Update the transcription with corrected text
        $transcription->update([
            'corrected_transcription' => $correctedText,
        ]);

        // Learn from correction (for future improvement)
        $this->learnFromCorrection($correction);

        Log::info('Transcription corrected', [
            'transcription_id' => $transcription->id,
            'correction_id' => $correction->id,
            'user_id' => $user->id,
        ]);

        return $correction;
    }

    /**
     * Learn from user corrections to improve future transcriptions
     */
    protected function learnFromCorrection(TranscriptionCorrection $correction): void
    {
        // This is a placeholder for machine learning integration
        // In a real application, you would:
        // 1. Store correction patterns
        // 2. Update ML model weights
        // 3. Build a training dataset from corrections
        // 4. Periodically retrain the model

        // For now, we just log the correction for future analysis
        Log::info('Learning from correction', [
            'correction_id' => $correction->id,
            'pattern' => [
                'from' => Str::limit($correction->original_text, 50),
                'to' => Str::limit($correction->corrected_text, 50),
            ],
        ]);
    }

    /**
     * Get transcription statistics for a team
     */
    public function getTeamStats(int $teamId): array
    {
        // Get all statistics in a single optimized query
        $stats = DocumentTranscription::where('team_id', $teamId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                AVG(CASE WHEN status = 'completed' THEN JSON_EXTRACT(metadata, '$.confidence') ELSE NULL END) as avg_confidence
            ")
            ->first();

        // Get total corrections count
        $totalCorrections = TranscriptionCorrection::whereHas('documentTranscription', function ($query) use ($teamId) {
            $query->where('team_id', $teamId);
        })->count();

        return [
            'total_transcriptions' => (int) ($stats->total ?? 0),
            'completed_transcriptions' => (int) ($stats->completed ?? 0),
            'pending_transcriptions' => (int) ($stats->pending ?? 0),
            'failed_transcriptions' => (int) ($stats->failed ?? 0),
            'total_corrections' => $totalCorrections,
            'avg_confidence' => round((float) ($stats->avg_confidence ?? 0), 2),
        ];
    }
}
