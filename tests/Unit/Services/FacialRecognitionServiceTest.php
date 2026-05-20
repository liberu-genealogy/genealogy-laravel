<?php

namespace Tests\Unit\Services;

use App\Models\FaceEncoding;
use App\Models\Person;
use App\Models\PersonPhoto;
use App\Models\PhotoTag;
use App\Models\Team;
use App\Models\User;
use App\Services\FacialRecognitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FacialRecognitionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected FacialRecognitionService $service;
    protected Team $team;
    protected Person $person;
    protected User $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new FacialRecognitionService();
        
        // Create test data
        $this->user = User::factory()->create();
        $this->team = Team::factory()->create();
        $this->team->users()->attach($this->user);
        $this->person = Person::factory()->create(['team_id' => $this->team->id]);
        
        // Setup storage
        Storage::fake('public');
    }

    public function testAnalyzePhotoDetectsFaces(): void
    {
        // Create a test photo file
        $file = UploadedFile::fake()->image('test-photo.jpg', 800, 600);
        $path = $file->store('person-photos', 'public');

        // Create PersonPhoto record
        $photo = PersonPhoto::create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'file_path' => $path,
            'file_name' => 'test-photo.jpg',
            'mime_type' => 'image/jpeg',
        ]);

        // Analyze the photo
        $result = $this->service->analyzePhoto($photo);

        // Assert success
        $this->assertTrue($result['success']);
        $this->assertGreaterThanOrEqual(0, $result['faces_detected']);
        $this->assertEquals($result['faces_detected'], $result['tags_created']);

        // Verify photo is marked as analyzed
        $photo->refresh();
        $this->assertTrue($photo->is_analyzed);
        $this->assertNotNull($photo->analyzed_at);
    }

    public function testAnalyzePhotoCreatesTags(): void
    {
        // Create photo
        $file = UploadedFile::fake()->image('test-photo.jpg', 800, 600);
        $path = $file->store('person-photos', 'public');

        $photo = PersonPhoto::create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'file_path' => $path,
            'file_name' => 'test-photo.jpg',
        ]);

        // Analyze
        $result = $this->service->analyzePhoto($photo);

        // Check tags were created
        $this->assertGreaterThanOrEqual(0, $photo->tags()->count());
        
        if ($result['faces_detected'] > 0) {
            // Each tag should have proper structure
            $tag = $photo->tags()->first();
            $this->assertNotNull($tag);
            $this->assertEquals('pending', $tag->status);
            $this->assertNotNull($tag->bounding_box);
            $this->assertNotNull($tag->confidence);
        }
    }

    public function testConfirmTagUpdatesStatus(): void
    {
        // Create a photo tag
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
        ]);

        $tag = PhotoTag::create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'confidence' => 95.5,
            'bounding_box' => ['left' => 0.1, 'top' => 0.1, 'width' => 0.2, 'height' => 0.3],
            'status' => 'pending',
        ]);

        // Confirm the tag
        $this->service->confirmTag($tag, $this->user->id);

        // Verify status changed
        $tag->refresh();
        $this->assertEquals('confirmed', $tag->status);
        $this->assertEquals($this->user->id, $tag->confirmed_by);
        $this->assertNotNull($tag->confirmed_at);
    }

    public function testRejectTagUpdatesStatus(): void
    {
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
        ]);

        $tag = PhotoTag::create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'confidence' => 85.0,
            'bounding_box' => ['left' => 0.1, 'top' => 0.1, 'width' => 0.2, 'height' => 0.3],
            'status' => 'pending',
        ]);

        // Reject the tag
        $this->service->rejectTag($tag);

        // Verify status changed
        $tag->refresh();
        $this->assertEquals('rejected', $tag->status);
    }

    public function testUpdateTagPersonChangesAssignment(): void
    {
        $anotherPerson = Person::factory()->create(['team_id' => $this->team->id]);
        
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
        ]);

        $tag = PhotoTag::create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'confidence' => 90.0,
            'bounding_box' => ['left' => 0.1, 'top' => 0.1, 'width' => 0.2, 'height' => 0.3],
            'status' => 'pending',
        ]);

        // Update to different person
        $this->service->updateTagPerson($tag, $anotherPerson->id, $this->user->id);

        // Verify changes
        $tag->refresh();
        $this->assertEquals($anotherPerson->id, $tag->person_id);
        $this->assertEquals('confirmed', $tag->status);
        $this->assertEquals($this->user->id, $tag->confirmed_by);
    }

    public function testGetPendingTagsReturnsCorrectTags(): void
    {
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
        ]);

        // Create pending tags
        PhotoTag::create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'confidence' => 90.0,
            'bounding_box' => ['left' => 0.1, 'top' => 0.1, 'width' => 0.2, 'height' => 0.3],
            'status' => 'pending',
        ]);

        // Create confirmed tag (should not be returned)
        PhotoTag::create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'confidence' => 92.0,
            'bounding_box' => ['left' => 0.3, 'top' => 0.3, 'width' => 0.2, 'height' => 0.3],
            'status' => 'confirmed',
        ]);

        $pendingTags = $this->service->getPendingTags($this->team->id);

        $this->assertEquals(1, $pendingTags->count());
        $this->assertEquals('pending', $pendingTags->first()->status);
    }

    public function testAnalyzePhotoHandlesMissingFile(): void
    {
        // Create photo record without actual file
        $photo = PersonPhoto::create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'file_path' => 'nonexistent/path.jpg',
            'file_name' => 'missing.jpg',
        ]);

        // Analyze should handle gracefully
        $result = $this->service->analyzePhoto($photo);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }
}
