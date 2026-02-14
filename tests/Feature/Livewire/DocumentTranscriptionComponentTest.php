<?php

namespace Tests\Feature\Livewire;

use App\Livewire\DocumentTranscriptionComponent;
use App\Models\DocumentTranscription;
use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentTranscriptionComponentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Team $team;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create();
        $this->team = Team::factory()->create(['user_id' => $this->user->id]);
        $this->user->current_team_id = $this->team->id;
        $this->user->save();
    }

    public function testComponentCanMount(): void
    {
        $this->actingAs($this->user);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->assertStatus(200);
    }

    public function testComponentLoadsTranscriptions(): void
    {
        $this->actingAs($this->user);

        // Create some transcriptions
        $transcriptions = DocumentTranscription::factory()
            ->count(3)
            ->create(['team_id' => $this->team->id, 'user_id' => $this->user->id]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->assertStatus(200)
            ->call('loadTranscriptions')
            ->assertSet('transcriptions', function ($value) use ($transcriptions) {
                return count($value) === 3;
            });
    }

    public function testCanUploadDocument(): void
    {
        $this->actingAs($this->user);

        $file = UploadedFile::fake()->image('test-document.jpg');

        Livewire::test(DocumentTranscriptionComponent::class)
            ->set('document', $file)
            ->call('uploadDocument')
            ->assertSet('successMessage', function ($value) {
                return str_contains($value, 'uploaded');
            });

        // Verify transcription was created
        $this->assertDatabaseHas('document_transcriptions', [
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
            'original_filename' => 'test-document.jpg',
        ]);
    }

    public function testCanSelectTranscription(): void
    {
        $this->actingAs($this->user);

        $transcription = DocumentTranscription::factory()->create([
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
            'raw_transcription' => 'Test transcription text',
        ]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('selectTranscription', $transcription->id)
            ->assertSet('currentTranscription.id', $transcription->id)
            ->assertSet('transcriptionText', 'Test transcription text');
    }

    public function testCanStartEditing(): void
    {
        $this->actingAs($this->user);

        $transcription = DocumentTranscription::factory()->create([
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('selectTranscription', $transcription->id)
            ->call('startEditing')
            ->assertSet('isEditing', true);
    }

    public function testCanCancelEditing(): void
    {
        $this->actingAs($this->user);

        $transcription = DocumentTranscription::factory()->create([
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
            'raw_transcription' => 'Original text',
        ]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('selectTranscription', $transcription->id)
            ->call('startEditing')
            ->set('transcriptionText', 'Modified text')
            ->call('cancelEditing')
            ->assertSet('isEditing', false)
            ->assertSet('transcriptionText', 'Original text');
    }

    public function testCanSaveCorrection(): void
    {
        $this->actingAs($this->user);

        $transcription = DocumentTranscription::factory()->create([
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
            'raw_transcription' => 'Original text',
        ]);

        $correctedText = 'Corrected text';

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('selectTranscription', $transcription->id)
            ->call('startEditing')
            ->set('transcriptionText', $correctedText)
            ->call('saveCorrection')
            ->assertSet('isEditing', false)
            ->assertSet('successMessage', function ($value) {
                return str_contains($value, 'saved');
            });

        // Verify correction was saved
        $this->assertDatabaseHas('transcription_corrections', [
            'document_transcription_id' => $transcription->id,
            'user_id' => $this->user->id,
            'corrected_text' => $correctedText,
        ]);

        // Verify transcription was updated
        $this->assertDatabaseHas('document_transcriptions', [
            'id' => $transcription->id,
            'corrected_transcription' => $correctedText,
        ]);
    }

    public function testCanDeleteTranscription(): void
    {
        $this->actingAs($this->user);

        $transcription = DocumentTranscription::factory()->create([
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('deleteTranscription', $transcription->id)
            ->assertSet('successMessage', function ($value) {
                return str_contains($value, 'deleted');
            });

        // Verify transcription was soft deleted
        $this->assertSoftDeleted('document_transcriptions', [
            'id' => $transcription->id,
        ]);
    }

    public function testOnlyShowsTranscriptionsForCurrentTeam(): void
    {
        $this->actingAs($this->user);

        // Create transcriptions for current team
        $currentTeamTranscriptions = DocumentTranscription::factory()
            ->count(2)
            ->create(['team_id' => $this->team->id]);

        // Create transcriptions for another team
        $otherTeam = Team::factory()->create();
        DocumentTranscription::factory()
            ->count(3)
            ->create(['team_id' => $otherTeam->id]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->assertSet('transcriptions', function ($value) {
                return count($value) === 2;
            });
    }

    public function testStatsAreCalculatedCorrectly(): void
    {
        $this->actingAs($this->user);

        DocumentTranscription::factory()->count(5)->create([
            'team_id' => $this->team->id,
            'status' => 'completed',
        ]);

        DocumentTranscription::factory()->count(2)->create([
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        $component = Livewire::test(DocumentTranscriptionComponent::class);
        $stats = $component->viewData('stats');

        $this->assertEquals(7, $stats['total_transcriptions']);
        $this->assertEquals(5, $stats['completed_transcriptions']);
        $this->assertEquals(2, $stats['pending_transcriptions']);
    }

    public function testValidatesFileUpload(): void
    {
        $this->actingAs($this->user);

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        Livewire::test(DocumentTranscriptionComponent::class)
            ->set('document', $file)
            ->call('uploadDocument')
            ->assertHasErrors(['document']);
    }
}
