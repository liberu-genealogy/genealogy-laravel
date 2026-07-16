<?php

namespace Tests\Feature\Search;

use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\Place;
use App\Models\Source;
use App\Models\Team;
use App\Models\User;
use App\Services\PersonSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    protected PersonSearchService $service;

    protected int $teamId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PersonSearchService;

        // All searchable entities are tenant-scoped (BelongsToTenant), so a
        // signed-in user with a current team is required.
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $user->current_team_id = $team->id;
        $user->save();
        $this->actingAs($user);
        $this->teamId = $team->id;
    }

    public function test_search_all_finds_person_by_name(): void
    {
        Person::factory()->create([
            'givn' => 'Aloysius',
            'surn' => 'Uniquesurname',
            'team_id' => $this->teamId,
        ]);

        $groups = $this->service->searchAll('Uniquesurname');

        $this->assertContains('Uniquesurname', $groups['people']->pluck('surn')->all());
    }

    public function test_search_all_finds_place_by_title(): void
    {
        Place::factory()->create(['title' => 'Transylvania']);

        $groups = $this->service->searchAll('Transylvania');

        $this->assertContains('Transylvania', $groups['places']->pluck('title')->all());
    }

    public function test_search_all_finds_source_by_name(): void
    {
        Source::factory()->create(['name' => 'DomesdayBook']);

        $groups = $this->service->searchAll('DomesdayBook');

        $this->assertContains('DomesdayBook', $groups['sources']->pluck('name')->all());
    }

    public function test_search_all_finds_event_by_title(): void
    {
        PersonEvent::factory()->create([
            'title' => 'GreatCoronation',
            'type' => 'coronation',
        ]);

        $groups = $this->service->searchAll('GreatCoronation');

        $this->assertContains('GreatCoronation', $groups['events']->pluck('title')->all());
    }

    public function test_phonetic_fallback_finds_similar_surname(): void
    {
        // "Smyth" is not a literal match for "Smith", but they share Soundex S530.
        Person::factory()->create([
            'givn' => 'John',
            'surn' => 'Smith',
            'team_id' => $this->teamId,
        ]);

        $groups = $this->service->searchAll('Smyth');

        $this->assertContains('Smith', $groups['people']->pluck('surn')->all());
    }

    public function test_year_filter_narrows_person_results(): void
    {
        Person::factory()->create([
            'givn' => 'Early',
            'surn' => 'Yeartest',
            'birth_year' => 1850,
            'team_id' => $this->teamId,
        ]);
        Person::factory()->create([
            'givn' => 'Late',
            'surn' => 'Yeartest',
            'birth_year' => 1950,
            'team_id' => $this->teamId,
        ]);

        $unfiltered = $this->service->searchAll('Yeartest');
        $this->assertCount(2, $unfiltered['people']);

        // Only the 1850 birth should survive a "to 1900" bound.
        $filtered = $this->service->searchAll('Yeartest', null, 1900);
        $this->assertCount(1, $filtered['people']);
        $this->assertSame(1850, (int) $filtered['people']->first()->birth_year);
    }
}
