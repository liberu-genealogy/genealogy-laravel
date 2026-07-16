<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\DnaMatching;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * dna_matchings had no team_id column, so BelongsToTenant's global scope
 * silently no-oped (Schema::hasColumn guard) and every team read every team's
 * DNA matches. With the column added, the scope keys off the acting user's
 * currentTeam. This proves a match created under team A is invisible under
 * team B and visible again under A.
 */
class DnaMatchingTenantScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_dna_matching_is_scoped_to_the_current_team(): void
    {
        $userA = User::factory()->withPersonalTeam()->create();
        $userB = User::factory()->withPersonalTeam()->create();

        // Create under team A — the trait auto-assigns team_id from the acting user's current team.
        $this->actingAs($userA);
        Filament::setTenant($userA->currentTeam);
        $match = DnaMatching::factory()->create();

        $this->assertSame($userA->currentTeam->id, $match->team_id);
        $this->assertTrue(DnaMatching::whereKey($match->id)->exists(), 'Match must be visible under its own team.');

        // Not visible under team B.
        $this->actingAs($userB);
        Filament::setTenant($userB->currentTeam);
        $this->assertFalse(DnaMatching::whereKey($match->id)->exists(), 'Match must NOT leak to another team.');
        $this->assertNull(DnaMatching::find($match->id));

        // Visible again once back on team A.
        $this->actingAs($userA);
        Filament::setTenant($userA->currentTeam);
        $this->assertTrue(DnaMatching::whereKey($match->id)->exists());
    }
}
