<?php

declare(strict_types=1);

namespace App\Services\FacialRecognition\Providers;

use App\Services\FacialRecognition\FacialRecognitionProviderInterface;
use Illuminate\Support\Facades\Log;

/**
 * Mock provider for facial recognition during development and testing
 * This allows the feature to work without requiring external API credentials
 */
class MockProvider implements FacialRecognitionProviderInterface
{
    public function detectFaces(string $imagePath): array
    {
        Log::info('MockProvider: Detecting faces in image', ['path' => $imagePath]);

        // Simulate detecting 1-3 random faces
        $faceCount = random_int(1, 3);
        $faces = [];

        for ($i = 0; $i < $faceCount; $i++) {
            $faces[] = [
                'bounding_box' => [
                    'left' => random_int(10, 40) / 100,
                    'top' => random_int(10, 40) / 100,
                    'width' => random_int(15, 30) / 100,
                    'height' => random_int(20, 35) / 100,
                ],
                'confidence' => random_int(85, 99) + (random_int(0, 99) / 100),
                'face_id' => 'mock_face_'.uniqid(),
            ];
        }

        return $faces;
    }

    public function matchFaces(string $imagePath, array $faceEncodings): array
    {
        Log::info('MockProvider: Matching faces', [
            'path' => $imagePath,
            'encoding_count' => count($faceEncodings),
        ]);

        $matches = [];

        // Simulate some matches with varying confidence
        foreach ($faceEncodings as $encoding) {
            // 60% chance of a match
            if (random_int(1, 100) <= 60) {
                $matches[] = [
                    'person_id' => $encoding['person_id'],
                    'confidence' => random_int(70, 95) + (random_int(0, 99) / 100),
                    'face_index' => random_int(0, 2),
                ];
            }
        }

        return $matches;
    }

    public function getFaceEncoding(string $imagePath, array $boundingBox): string
    {
        Log::info('MockProvider: Getting face encoding', [
            'path' => $imagePath,
            'bbox' => $boundingBox,
        ]);

        // Generate a mock encoding (random base64 string)
        return base64_encode(random_bytes(128));
    }

    public function isAvailable(): bool
    {
        // Mock provider is always available
        return true;
    }
}
