<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Livewire\DocumentTranscriptionComponent;
use App\Models\DocumentTranscription;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * A transcription belongs to a team, but the model carried no tenant scope, so
 * selectTranscription() loaded any team's row by raw id and saveCorrection()
 * then wrote to it — a cross-team read and write reachable over the wire on a
 * plain web route. These guard that the tenant boundary holds on both.
 */
class DocumentTranscriptionTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_member_cannot_read_another_teams_transcription(): void
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $othersTranscription = DocumentTranscription::factory()->create([
            'team_id' => Team::factory(),
            'raw_transcription' => 'SECRET other-team parish record',
        ]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->call('selectTranscription', $othersTranscription->id)
            ->assertSet('transcriptionText', '')
            ->assertSet('currentTranscription', null);
    }

    public function test_a_member_cannot_overwrite_another_teams_transcription(): void
    {
        // Owner of their own team → passes the update-tier gate, so the tenant
        // boundary is the only thing that can stop the write.
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $othersTranscription = DocumentTranscription::factory()->create([
            'team_id' => Team::factory(),
            'raw_transcription' => 'original other-team text',
            'corrected_transcription' => null,
        ]);

        Livewire::test(DocumentTranscriptionComponent::class)
            ->set('transcriptionText', 'HACKED by another team')
            ->call('selectTranscription', $othersTranscription->id)
            ->call('saveCorrection');

        $this->assertNull(
            $othersTranscription->fresh()->corrected_transcription,
            'A member of another team overwrote a transcription.'
        );
    }
}
