<?php

namespace Tests\Unit\Services;

use App\Services\FindMyPastMatchingProvider;
use App\Models\Person;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FindMyPastMatchingProviderTest extends TestCase
{
    use RefreshDatabase;

    private FindMyPastMatchingProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new FindMyPastMatchingProvider();
    }

    public function test_search_records_returns_array(): void
    {
        $person = Person::factory()->create([
            'firstname' => 'John',
            'surname' => 'Smith',
        ]);

        $results = $this->provider->searchRecords($person);

        $this->assertIsArray($results);
    }

    public function test_search_records_includes_newspaper_matches_for_deceased_person(): void
    {
        $person = Person::factory()->create([
            'firstname' => 'John',
            'surname' => 'Smith',
        ]);

        // Set death date
        $person->deathday = now()->subYears(50);
        $person->save();

        $results = $this->provider->searchRecords($person, 'newspaper');

        $this->assertNotEmpty($results);
        $newspaperMatches = array_filter($results, fn($match) => $match['record_type'] === 'newspaper');
        $this->assertNotEmpty($newspaperMatches);
    }

    public function test_search_records_includes_census_matches(): void
    {
        $person = Person::factory()->create([
            'firstname' => 'John',
            'surname' => 'Smith',
        ]);

        // Set birth date to ensure person would appear in census
        $person->birthday = now()->subYears(150);
        $person->save();

        $results = $this->provider->searchRecords($person, 'census');

        $this->assertNotEmpty($results);
        $censusMatches = array_filter($results, fn($match) => $match['record_type'] === 'census');
        $this->assertNotEmpty($censusMatches);
    }

    public function test_search_records_includes_parish_matches(): void
    {
        $person = Person::factory()->create([
            'firstname' => 'John',
            'surname' => 'Smith',
        ]);

        $person->birthday = now()->subYears(100);
        $person->save();

        $results = $this->provider->searchRecords($person, 'parish');

        $this->assertNotEmpty($results);
        $parishMatches = array_filter($results, fn($match) => $match['record_type'] === 'parish');
        $this->assertNotEmpty($parishMatches);
    }

    public function test_confidence_scores_are_within_valid_range(): void
    {
        $person = Person::factory()->create([
            'firstname' => 'John',
            'surname' => 'Smith',
        ]);

        $person->birthday = now()->subYears(100);
        $person->deathday = now()->subYears(50);
        $person->save();

        $results = $this->provider->searchRecords($person);

        foreach ($results as $match) {
            $this->assertArrayHasKey('confidence_score', $match);
            $this->assertGreaterThanOrEqual(0.0, $match['confidence_score']);
            $this->assertLessThanOrEqual(1.0, $match['confidence_score']);
        }
    }

    public function test_matches_include_required_fields(): void
    {
        $person = Person::factory()->create([
            'firstname' => 'John',
            'surname' => 'Smith',
        ]);

        $person->birthday = now()->subYears(100);
        $person->save();

        $results = $this->provider->searchRecords($person);

        if (!empty($results)) {
            $match = $results[0];
            
            $this->assertArrayHasKey('record_type', $match);
            $this->assertArrayHasKey('source', $match);
            $this->assertArrayHasKey('tree_id', $match);
            $this->assertArrayHasKey('person_id', $match);
            $this->assertArrayHasKey('confidence_score', $match);
            $this->assertArrayHasKey('data', $match);
            
            $this->assertEquals('findmypast', $match['source']);
        }
    }
}
