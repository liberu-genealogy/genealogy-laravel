<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\PersonPhoto;
use App\Models\PhotoTag;
use App\Services\FacialRecognitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

/**
 * A provider that returns a face without a confidence figure has not measured
 * one. The service must store that absence as null, not substitute 0 — a stored
 * 0 reads in the review interface as "certainly not this person", a claim the
 * provider never made. Guards the fix at FacialRecognitionService::createPhotoTags.
 */
final class FaceMatchConfidenceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  list<array<string, mixed>>  $detectedFaces
     * @param  list<array<string, mixed>>  $matches
     */
    private function createTags(PersonPhoto $photo, array $detectedFaces, array $matches): void
    {
        $method = new ReflectionMethod(FacialRecognitionService::class, 'createPhotoTags');
        $method->invoke(new FacialRecognitionService, $photo, $detectedFaces, $matches);
    }

    public function test_a_face_without_a_reported_confidence_stores_no_confidence(): void
    {
        $photo = PersonPhoto::factory()->create();

        $this->createTags($photo, [['bounding_box' => ['x' => 1, 'y' => 2, 'w' => 3, 'h' => 4]]], []);

        $this->assertDatabaseHas('photo_tags', ['photo_id' => $photo->id, 'confidence' => null]);
        $this->assertDatabaseMissing('photo_tags', ['photo_id' => $photo->id, 'confidence' => 0]);
    }

    public function test_a_reported_confidence_is_stored_unchanged(): void
    {
        $photo = PersonPhoto::factory()->create();

        $this->createTags($photo, [['confidence' => 0.42, 'bounding_box' => ['x' => 1]]], []);

        $tag = PhotoTag::where('photo_id', $photo->id)->firstOrFail();
        $this->assertSame('0.42', (string) $tag->confidence);
    }

    public function test_a_matched_face_stores_the_match_confidence(): void
    {
        $photo = PersonPhoto::factory()->create();

        $this->createTags(
            $photo,
            [['bounding_box' => ['x' => 1]]],
            [['face_index' => 0, 'person_id' => null, 'confidence' => 0.87]],
        );

        $tag = PhotoTag::where('photo_id', $photo->id)->firstOrFail();
        $this->assertSame('0.87', (string) $tag->confidence);
    }
}
