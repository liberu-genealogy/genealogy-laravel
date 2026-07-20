<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\App\Resources\DnaMatchingResource\Pages\CreateDnaMatching;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * predicted_relationship was free text, so a user could type the exact string the
 * prune command uses as its "no comparison ran" marker into a hand-curated row and
 * have it deleted (with its reciprocal) on the next prune. It is now a Select over
 * the estimator's own labels, which do not include the marker.
 */
final class DnaMatchingRelationshipFieldTest extends TestCase
{
    use RefreshDatabase;

    private function actAsTeamMember(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        return $user;
    }

    public function test_the_prune_marker_cannot_be_submitted_as_a_predicted_relationship(): void
    {
        $user = $this->actAsTeamMember();

        Livewire::test(CreateDnaMatching::class)
            ->fillForm([
                'user_id' => $user->id,
                'predicted_relationship' => 'Unknown (Basic Analysis)',
            ])
            ->call('create')
            ->assertHasFormErrors(['predicted_relationship']);

        $this->assertDatabaseCount('dna_matchings', 0);
    }
}
