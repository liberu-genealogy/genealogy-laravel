<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Person;
use App\Models\User;
use App\Services\DuplicateCheckerService;
use App\Services\FamilyMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Both services eager-loaded relations that do not exist on Person — there is no
 * `user` relation (people are owned by a team, not a user, and there is no
 * user_id column) and no `gedcom` relation. Eloquent only resolves a relation
 * name at query time, so both threw "Call to undefined method" on every call:
 * the duplicate-check action fataled, and social family matching silently
 * matched nobody. Neither service had a test.
 *
 * larastan's relationExistence check is what surfaced these; plain PHPStan
 * cannot see relation names at all.
 */
class DeadRelationRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_check_runs_for_a_users_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $teamId = $user->current_team_id;

        Person::factory()->create(['givn' => 'John', 'surn' => 'Doe', 'team_id' => $teamId]);
        Person::factory()->create(['givn' => 'John', 'surn' => 'Doe', 'team_id' => $teamId]);

        $check = (new DuplicateCheckerService)->runDuplicateCheck($user);

        $this->assertSame('completed', $check->status);
    }

    public function test_duplicate_check_only_sees_its_own_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $other = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create(['givn' => 'Ada', 'surn' => 'Byron', 'team_id' => $other->current_team_id]);

        $check = (new DuplicateCheckerService)->runDuplicateCheck($user);

        $this->assertSame('completed', $check->status);
        $this->assertSame(0, $check->duplicates_found);
    }

    public function test_family_matching_reads_surnames_from_the_users_teams(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create(['surn' => 'Lovelace', 'team_id' => $user->current_team_id]);
        Person::factory()->create(['surn' => 'Babbage', 'team_id' => $user->current_team_id]);

        // Protected, and its public callers reach out to social providers.
        $method = new ReflectionMethod(FamilyMatchingService::class, 'getUserFamilySurnames');
        $method->setAccessible(true);

        $surnames = $method->invoke(new FamilyMatchingService, $user);

        sort($surnames);
        $this->assertSame(['Babbage', 'Lovelace'], $surnames);
    }

    public function test_family_matching_excludes_other_teams_surnames(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $other = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create(['surn' => 'Mine', 'team_id' => $user->current_team_id]);
        Person::factory()->create(['surn' => 'Theirs', 'team_id' => $other->current_team_id]);

        $method = new ReflectionMethod(FamilyMatchingService::class, 'getUserFamilySurnames');
        $method->setAccessible(true);

        $this->assertSame(['Mine'], $method->invoke(new FamilyMatchingService, $user));
    }

    /**
     * with('parents') threw on every render: parents() returns a Collection built
     * from childInFamily->husband/wife, so it is not eager-loadable.
     */
    public function test_pedigree_chart_widget_eager_load_does_not_throw(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create();

        $people = Person::with(['childInFamily.husband', 'childInFamily.wife'])->get();

        $this->assertCount(1, $people);
    }
}
