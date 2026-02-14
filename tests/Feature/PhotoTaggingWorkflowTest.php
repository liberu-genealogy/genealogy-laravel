<?php

namespace Tests\Feature;

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

class PhotoTaggingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Team $team;
    protected Person $person;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->team = Team::factory()->create();
        $this->team->users()->attach($this->user, ['role' => 'admin']);
        $this->user->switchTeam($this->team);
        $this->person = Person::factory()->create(['team_id' => $this->team->id]);

        Storage::fake('public');
    }

    public function testCompletePhotoTaggingWorkflow(): void
    {
        // Step 1: Create and upload a photo
        $file = UploadedFile::fake()->image('family-photo.jpg', 1200, 800);
        $path = $file->store('person-photos', 'public');

        $photo = PersonPhoto::create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'file_path' => $path,
            'file_name' => 'family-photo.jpg',
            'mime_type' => 'image/jpeg',
        ]);

        $this->assertFalse($photo->is_analyzed);

        // Step 2: Analyze the photo with facial recognition
        $service = new FacialRecognitionService();
        $result = $service->analyzePhoto($photo);

        $this->assertTrue($result['success']);

        // Step 3: Verify photo is now analyzed
        $photo->refresh();
        $this->assertTrue($photo->is_analyzed);
        $this->assertNotNull($photo->analyzed_at);

        // Step 4: If faces were detected, verify tags were created
        if ($result['faces_detected'] > 0) {
            $this->assertEquals($result['faces_detected'], $photo->tags()->count());

            // Step 5: Get a pending tag and confirm it
            $tag = $photo->tags()->where('status', 'pending')->first();
            $this->assertNotNull($tag);

            $service->confirmTag($tag, $this->user->id);

            $tag->refresh();
            $this->assertEquals('confirmed', $tag->status);
            $this->assertEquals($this->user->id, $tag->confirmed_by);
        }
    }

    public function testUserCanReviewAndCorrectTags(): void
    {
        $anotherPerson = Person::factory()->create(['team_id' => $this->team->id]);

        // Create a photo with a tag that needs correction
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'is_analyzed' => true,
        ]);

        $tag = PhotoTag::factory()->create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        // User corrects the tag to point to a different person
        $service = new FacialRecognitionService();
        $service->updateTagPerson($tag, $anotherPerson->id, $this->user->id);

        $tag->refresh();
        $this->assertEquals($anotherPerson->id, $tag->person_id);
        $this->assertEquals('confirmed', $tag->status);
    }

    public function testUserCanRejectIncorrectTags(): void
    {
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'is_analyzed' => true,
        ]);

        $tag = PhotoTag::factory()->create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        // User rejects the tag
        $service = new FacialRecognitionService();
        $service->rejectTag($tag);

        $tag->refresh();
        $this->assertEquals('rejected', $tag->status);
    }

    public function testPersonPhotoRelationshipWorks(): void
    {
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
        ]);

        // Test relationship from Person to Photos
        $this->assertTrue($this->person->photos->contains($photo));

        // Test relationship from Photo to Person
        $this->assertEquals($this->person->id, $photo->person->id);
    }

    public function testPhotoTagsAreLinkedToPerson(): void
    {
        $photo = PersonPhoto::factory()->create([
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
        ]);

        $tag = PhotoTag::factory()->confirmed()->create([
            'photo_id' => $photo->id,
            'person_id' => $this->person->id,
            'team_id' => $this->team->id,
            'confirmed_by' => $this->user->id,
        ]);

        // Verify the tag is accessible from the person
        $this->assertTrue($this->person->photoTags->contains($tag));
        $this->assertTrue($this->person->confirmedPhotoTags->contains($tag));
    }
}
