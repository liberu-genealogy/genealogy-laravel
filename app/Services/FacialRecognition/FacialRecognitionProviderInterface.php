<?php

namespace App\Services\FacialRecognition;

interface FacialRecognitionProviderInterface
{
    /**
     * Detect faces in an image
     *
     * @param string $imagePath Path to the image file
     * @return array Array of detected faces with bounding boxes and metadata
     */
    public function detectFaces(string $imagePath): array;

    /**
     * Compare a face with existing face encodings
     *
     * @param string $imagePath Path to the image file
     * @param array $faceEncodings Array of existing face encodings to compare against
     * @return array Array of matches with person IDs and confidence scores
     */
    public function matchFaces(string $imagePath, array $faceEncodings): array;

    /**
     * Get face encoding from an image
     *
     * @param string $imagePath Path to the image file
     * @param array $boundingBox Bounding box coordinates of the face
     * @return string Face encoding data
     */
    public function getFaceEncoding(string $imagePath, array $boundingBox): string;

    /**
     * Check if the provider is available and configured
     *
     * @return bool
     */
    public function isAvailable(): bool;
}
