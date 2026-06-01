<?php

namespace Tests\Unit\Services;

use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use App\Services\PersonSearchService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PersonSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PersonSearchService;
    }

    private function actingAsUserWithTeam(?Team $team = null): User
    {
        $user = User::factory()->create();
        $t = $team ?? Team::factory()->create(['user_id' => $user->id]);
        $user->current_team_id = $t->id;
        $user->save();
        $this->actingAs($user);

        return $user;
    }

    // ---------------------------------------------------
    // isLiving() on Person model
    // ---------------------------------------------------

    public function test_person_is_living_when_no_death_and_recent_birth(): void
    {
        $person = Person::factory()->make([
            'birthday' => now()->subYears(30),
            'deathday' => null,
        ]);

        $this->assertTrue($person->isLiving());
    }

    public function test_person_is_not_living_when_death_recorded(): void
    {
        $person = Person::factory()->make([
            'birthday' => now()->subYears(200),
            'deathday' => now()->subYears(130),
        ]);

        $this->assertFalse($person->isLiving());
    }

    public function test_person_is_not_living_when_born_over100_years_ago(): void
    {
        $person = Person::factory()->make([
            'birthday' => now()->subYears(120),
            'deathday' => null,
        ]);

        $this->assertFalse($person->isLiving());
    }

    public function test_person_is_living_when_no_birth_or_death(): void
    {
        $person = Person::factory()->make([
            'birthday' => null,
            'birth_year' => null,
            'deathday' => null,
        ]);

        $this->assertTrue($person->isLiving());
    }

    // ---------------------------------------------------
    // searchOwnTeam()
    // ---------------------------------------------------

    public function test_search_own_team_finds_matching_people(): void
    {
        $user = $this->actingAsUserWithTeam();
        $teamId = $user->currentTeam->id;

        Person::factory()->create([
            'givn' => 'Johann',
            'surn' => 'Bach',
            'team_id' => $teamId,
        ]);

        Person::factory()->create([
            'givn' => 'Wolfgang',
            'surn' => 'Mozart',
            'team_id' => $teamId,
        ]);

        $results = $this->service->searchOwnTeam('Bach');

        $this->assertGreaterThanOrEqual(1, $results->total());
        $names = collect($results->items())->pluck('surn')->toArray();
        $this->assertContains('Bach', $names);
    }

    public function test_search_own_team_does_not_return_other_team_data(): void
    {
        $user = $this->actingAsUserWithTeam();
        $teamId = $user->currentTeam->id;

        $otherTeam = Team::factory()->create();

        Person::factory()->create([
            'givn' => 'My',
            'surn' => 'Person',
            'team_id' => $teamId,
        ]);

        Person::factory()->create([
            'givn' => 'Other',
            'surn' => 'Person',
            'team_id' => $otherTeam->id,
        ]);

        $results = $this->service->searchOwnTeam('Person');

        $teamIds = collect($results->items())->pluck('team_id')->unique()->toArray();
        $this->assertEquals([$teamId], $teamIds);
    }

    // ---------------------------------------------------
    // searchGlobal() — cross-team with privacy
    // ---------------------------------------------------

    public function test_global_search_includes_deceased_from_public_teams(): void
    {
        $user = $this->actingAsUserWithTeam();

        $publicTeam = Team::factory()->create(['is_public' => true]);

        // Deceased person in public team — should appear
        Person::factory()->create([
            'givn' => 'Abraham',
            'surn' => 'Lincoln',
            'birthday' => Carbon::parse('1809-02-12'),
            'deathday' => Carbon::parse('1865-04-15'),
            'team_id' => $publicTeam->id,
        ]);

        $results = $this->service->searchGlobal('Lincoln');

        $this->assertGreaterThanOrEqual(1, $results->total());
        $names = collect($results->items())->pluck('surn')->toArray();
        $this->assertContains('Lincoln', $names);
    }

    public function test_global_search_excludes_living_from_public_teams(): void
    {
        $user = $this->actingAsUserWithTeam();

        $publicTeam = Team::factory()->create(['is_public' => true]);

        // Living person in public team — should NOT appear
        Person::factory()->create([
            'givn' => 'Living',
            'surn' => 'PersonTest',
            'birthday' => now()->subYears(30),
            'deathday' => null,
            'team_id' => $publicTeam->id,
        ]);

        $results = $this->service->searchGlobal('PersonTest');

        $names = collect($results->items())->pluck('surn')->toArray();
        $this->assertNotContains('PersonTest', $names);
    }

    public function test_global_search_excludes_private_teams(): void
    {
        $user = $this->actingAsUserWithTeam();

        $privateTeam = Team::factory()->create(['is_public' => false]);

        Person::factory()->create([
            'givn' => 'Secret',
            'surn' => 'PersonHidden',
            'birthday' => Carbon::parse('1800-01-01'),
            'deathday' => Carbon::parse('1870-01-01'),
            'team_id' => $privateTeam->id,
        ]);

        $results = $this->service->searchGlobal('PersonHidden');

        $names = collect($results->items())->pluck('surn')->toArray();
        $this->assertNotContains('PersonHidden', $names);
    }

    public function test_global_search_includes_own_team_living_people(): void
    {
        $user = $this->actingAsUserWithTeam();
        $teamId = $user->currentTeam->id;

        // Living person in own team — should still appear
        Person::factory()->create([
            'givn' => 'MyLiving',
            'surn' => 'Relative',
            'birthday' => now()->subYears(25),
            'deathday' => null,
            'team_id' => $teamId,
        ]);

        $results = $this->service->searchGlobal('Relative');

        $names = collect($results->items())->pluck('surn')->toArray();
        $this->assertContains('Relative', $names);
    }

    // ---------------------------------------------------
    // Person scopes
    // ---------------------------------------------------

    public function test_deceased_scope_filters_correctly(): void
    {
        $user = $this->actingAsUserWithTeam();
        $teamId = $user->currentTeam->id;

        // Deceased person
        Person::factory()->create([
            'givn' => 'Dead',
            'surn' => 'Person',
            'deathday' => Carbon::parse('1900-01-01'),
            'team_id' => $teamId,
        ]);

        // Old person (born 120 years ago, no death but historically safe)
        Person::factory()->create([
            'givn' => 'Old',
            'surn' => 'Person',
            'birthday' => now()->subYears(120),
            'birth_year' => now()->subYears(120)->year,
            'deathday' => null,
            'team_id' => $teamId,
        ]);

        // Living person
        Person::factory()->create([
            'givn' => 'Young',
            'surn' => 'Person',
            'birthday' => now()->subYears(25),
            'birth_year' => now()->subYears(25)->year,
            'deathday' => null,
            'team_id' => $teamId,
        ]);

        $deceased = Person::deceased()->get();
        $deceasedNames = $deceased->pluck('givn')->toArray();

        $this->assertContains('Dead', $deceasedNames);
        $this->assertContains('Old', $deceasedNames);
        $this->assertNotContains('Young', $deceasedNames);
    }

    public function test_living_scope_filters_correctly(): void
    {
        $user = $this->actingAsUserWithTeam();
        $teamId = $user->currentTeam->id;

        Person::factory()->create([
            'givn' => 'Alive',
            'surn' => 'NowPerson',
            'birthday' => now()->subYears(25),
            'birth_year' => now()->subYears(25)->year,
            'deathday' => null,
            'team_id' => $teamId,
        ]);

        Person::factory()->create([
            'givn' => 'Historical',
            'surn' => 'OldPerson',
            'birthday' => Carbon::parse('1800-01-01'),
            'deathday' => Carbon::parse('1870-01-01'),
            'team_id' => $teamId,
        ]);

        $living = Person::living()->get();
        $livingNames = $living->pluck('givn')->toArray();

        $this->assertContains('Alive', $livingNames);
        $this->assertNotContains('Historical', $livingNames);
    }

    // ---------------------------------------------------
    // Team is_public
    // ---------------------------------------------------

    public function test_team_is_public_defaults_false(): void
    {
        $team = Team::factory()->create();
        $this->assertFalse($team->is_public);
    }

    public function test_team_can_be_set_public(): void
    {
        $team = Team::factory()->create(['is_public' => true]);
        $this->assertTrue($team->fresh()->is_public);
    }
}
