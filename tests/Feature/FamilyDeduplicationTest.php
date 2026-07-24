<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The deduplicate_families migration adds a unique index on
 * (husband_id, wife_id, team_id) so a re-import can't silently re-duplicate a
 * couple. Single-parent families (a null slot) are exempt — NULLs compare
 * distinct in a unique index.
 */
class FamilyDeduplicationTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_a_second_family_for_the_same_couple_is_rejected(): void
    {
        $this->actingUser();
        $husband = Person::factory()->create();
        $wife = Person::factory()->create();

        Family::factory()->create(['husband_id' => $husband->id, 'wife_id' => $wife->id]);

        $this->expectException(QueryException::class);
        Family::factory()->create(['husband_id' => $husband->id, 'wife_id' => $wife->id]);
    }

    public function test_multiple_single_parent_families_with_the_same_parent_are_allowed(): void
    {
        $this->actingUser();
        $mother = Person::factory()->create();

        // Two mother-only families (null husband) must coexist — NULLs are distinct.
        Family::factory()->create(['husband_id' => null, 'wife_id' => $mother->id]);
        $second = Family::factory()->create(['husband_id' => null, 'wife_id' => $mother->id]);

        $this->assertDatabaseHas('families', ['id' => $second->id]);
    }
}
