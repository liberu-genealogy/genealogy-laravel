<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\DuplicateMatch;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * DuplicateMatch (and SmartMatch) previously used neither BelongsToTenant nor a
 * team_id column, so every team read every team's duplicate/smart matches. With
 * the trait + team_id migration added, the global scope keys off the acting
 * user's currentTeam. This proves a match created under team A is invisible
 * under team B and visible again under A — the same harness the DNA scope test
 * and the Filament mount tests use (User::withPersonalTeam + Filament::setTenant).
 */
class GlobalScopeAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_match_is_scoped_to_the_current_team(): void
    {
        $userA = User::factory()->withPersonalTeam()->create();
        $userB = User::factory()->withPersonalTeam()->create();

        // Act as team A: the two people and the match all auto-assign team_id = A.
        $this->actingAs($userA);
        Filament::setTenant($userA->currentTeam);

        $primary = Person::factory()->create();
        $duplicate = Person::factory()->create();

        $match = DuplicateMatch::create([
            'primary_person_id' => $primary->id,
            'duplicate_person_id' => $duplicate->id,
            'confidence_score' => 0.9500,
            'status' => 'pending',
        ]);

        $this->assertSame($userA->currentTeam->id, $match->team_id);
        $this->assertTrue(DuplicateMatch::whereKey($match->id)->exists(), 'Match must be visible under its own team.');

        // Not visible under team B.
        $this->actingAs($userB);
        Filament::setTenant($userB->currentTeam);
        $this->assertFalse(DuplicateMatch::whereKey($match->id)->exists(), 'Match must NOT leak to another team.');
        $this->assertNull(DuplicateMatch::find($match->id));

        // Visible again once back on team A.
        $this->actingAs($userA);
        Filament::setTenant($userA->currentTeam);
        $this->assertTrue(DuplicateMatch::whereKey($match->id)->exists());
    }
}
