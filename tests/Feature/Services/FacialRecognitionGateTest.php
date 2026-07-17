<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Person;
use App\Models\PersonPhoto;
use App\Models\PhotoTag;
use App\Models\User;
use App\Services\FacialRecognitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Facial recognition defaulted to MockProvider, which is the only implementation
 * that exists (aws/azure are commented out). It invents 1-3 faces at random
 * bounding boxes, assigns a person on a 60% coin flip with a 70-95%
 * "confidence", and returns random_bytes() as the face encoding — and those were
 * persisted as PhotoTag and FaceEncoding rows and shown for review as
 * detections. The provider interface declared isAvailable() but nothing ever
 * called it; it is now the gate, and the config defaults to 'none'.
 */
class FacialRecognitionGateTest extends TestCase
{
    use RefreshDatabase;

    private function photo(User $user): PersonPhoto
    {
        Storage::fake('public');

        $person = Person::factory()->create(['team_id' => $user->current_team_id]);
        $path = UploadedFile::fake()->image('face.jpg')->store('photos', 'public');

        return PersonPhoto::factory()->create([
            'person_id' => $person->id,
            'team_id' => $user->current_team_id,
            'file_path' => $path,
        ]);
    }

    public function test_the_default_config_has_no_provider(): void
    {
        // The env var is unset in tests, so this is what a fresh install gets.
        $this->assertSame('none', config('services.facial_recognition.provider'));
        $this->assertFalse((new FacialRecognitionService)->isAvailable());
    }

    public function test_analyze_photo_creates_no_tags_without_a_provider(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $photo = $this->photo($user);

        $result = (new FacialRecognitionService)->analyzePhoto($photo);

        $this->assertFalse($result['success']);
        $this->assertSame('Facial recognition is not configured.', $result['error']);
        $this->assertDatabaseCount('photo_tags', 0);
    }

    public function test_no_face_encoding_is_stored_without_a_provider(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $photo = $this->photo($user);

        $tag = PhotoTag::factory()->create([
            'photo_id' => $photo->id,
            'person_id' => $photo->person_id,
            'team_id' => $user->current_team_id,
            'status' => 'confirmed',
            'bounding_box' => ['left' => 0.1, 'top' => 0.1, 'width' => 0.2, 'height' => 0.2],
        ]);

        // MockProvider returned base64_encode(random_bytes(128)) here, so a confirmed
        // tag stored random noise as that person's face signature.
        $this->assertNull((new FacialRecognitionService)->createFaceEncoding($tag));
        $this->assertDatabaseCount('face_encodings', 0);
    }

    public function test_the_mock_provider_still_works_when_explicitly_opted_in(): void
    {
        config(['services.facial_recognition.provider' => 'mock']);

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $photo = $this->photo($user);

        $service = new FacialRecognitionService;

        $this->assertTrue($service->isAvailable());

        // Opting in is deliberate, so it behaves exactly as before — including
        // fabricating tags. That is the point of it being opt-in.
        $result = $service->analyzePhoto($photo);

        $this->assertTrue($result['success']);
    }

    public function test_an_unknown_provider_name_yields_no_provider_rather_than_the_mock(): void
    {
        // The old match had `default => new MockProvider`, so a typo in the env var
        // silently fabricated data instead of failing closed.
        config(['services.facial_recognition.provider' => 'aws']);

        $this->assertFalse((new FacialRecognitionService)->isAvailable());
    }
}
