<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Livewire\DocumentTranscriptionComponent;
use App\Models\DocumentTranscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Correcting and deleting a team's transcriptions are writes on the team's
 * records, reachable over the wire on a plain web route. Delete already
 * confirmed the record belonged to the team; neither checked what the member
 * may do to it, so a viewer could delete the team's transcriptions.
 */
class DocumentTranscriptionTierEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_viewer_cannot_delete_a_transcription(): void
    {
        $team = $this->actingAsMember('viewer');
        $transcription = DocumentTranscription::factory()->create(['team_id' => $team]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('deleteTranscription', $transcription->id)
            ->assertForbidden();

        $this->assertNotSoftDeleted($transcription, [], 'A viewer deleted a transcription.');
    }

    public function test_a_viewer_cannot_save_a_correction(): void
    {
        $this->actingAsMember('viewer');

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('saveCorrection')
            ->assertForbidden();
    }

    /**
     * Uploading runs the transcription and writes a record, so it is a create.
     * The guard is reached before any file handling, so an image is not needed
     * to prove a viewer is refused.
     */
    public function test_a_viewer_cannot_upload_a_document(): void
    {
        $this->actingAsMember('viewer');

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('uploadDocument')
            ->assertForbidden();

        $this->assertSame(0, DocumentTranscription::count(), 'A viewer created a transcription by uploading.');
    }

    public function test_an_editor_may_delete_a_transcription(): void
    {
        $team = $this->actingAsMember('editor');
        $transcription = DocumentTranscription::factory()->create(['team_id' => $team]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('deleteTranscription', $transcription->id)
            ->assertOk();

        $this->assertSoftDeleted($transcription);
    }

    private function actingAsMember(string $tier): int
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => $tier]);

        $member->forceFill(['current_team_id' => $owner->current_team_id])->save();
        $this->actingAs($member->fresh());

        return $owner->current_team_id;
    }
}
