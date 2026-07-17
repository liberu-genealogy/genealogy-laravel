<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Person;
use App\Models\SmartMatch;
use App\Models\User;
use App\Services\RecordMatcher\Providers\ExternalRecordProviderInterface;
use App\Services\SmartMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionProperty;
use Tests\TestCase;

/**
 * Smart matching used to fall back to a simulation whenever no provider was
 * configured — which is always, without API keys. It invented 2-8 matches per
 * person (names, dates, places, parents, spouse, children and a plausible
 * source_url), persisted them as SmartMatch rows, and the UI showed them as
 * records found on Ancestry/MyHeritage/FindMyPast.
 *
 * The provider path that was supposed to replace it had never run: it scored
 * candidates via $match['name'], a key real providers do not return, so every
 * candidate threw a TypeError that was caught and logged as a provider failure.
 */
class SmartMatchingServiceTest extends TestCase
{
    use RefreshDatabase;

    private function withProviders(SmartMatchingService $service, array $providers): SmartMatchingService
    {
        $property = new ReflectionProperty(SmartMatchingService::class, 'providers');
        $property->setAccessible(true);
        $property->setValue($service, $providers);

        return $service;
    }

    public function test_no_configured_provider_persists_no_matches(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'team_id' => $user->current_team_id,
            'child_in_family_id' => null,
        ]);

        // No API keys in the test env, so no provider is configured.
        $matches = (new SmartMatchingService)->findSmartMatches($user);

        $this->assertCount(0, $matches);
        $this->assertDatabaseCount('smart_matches', 0);
    }

    public function test_a_configured_provider_scores_its_candidates_and_persists_matches(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $person = Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'team_id' => $user->current_team_id,
            'child_in_family_id' => null,
        ]);

        $service = $this->withProviders(new SmartMatchingService, [
            'ancestry' => new StubRecordProvider([[
                'id' => 'ANC-1',
                'external_id' => 'ANC-1',
                'tree_id' => 'tree-9',
                // The real provider shape: first_name/last_name, no 'name'.
                'first_name' => 'Ada',
                'last_name' => 'Lovelace',
                'birth_date' => null,
                'death_date' => null,
                'birth_place' => null,
            ]]),
        ]);

        $matches = $service->findSmartMatches($user);

        $this->assertCount(1, $matches);
        $this->assertDatabaseHas('smart_matches', [
            'person_id' => $person->id,
            'external_person_id' => 'ANC-1',
            'match_source' => 'ancestry',
        ]);
    }

    public function test_confidence_is_deterministic(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'team_id' => $user->current_team_id,
            'child_in_family_id' => null,
            'birthday_plac' => 'London, England',
        ]);

        $candidate = [[
            'id' => 'ANC-1',
            'tree_id' => 'tree-9',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'birth_place' => 'London, England',
        ]];

        $scores = [];
        for ($i = 0; $i < 3; $i++) {
            SmartMatch::query()->forceDelete();
            $service = $this->withProviders(new SmartMatchingService, [
                'ancestry' => new StubRecordProvider($candidate),
            ]);
            $scores[] = $service->findSmartMatches($user)->first()->confidence_score;
        }

        // A tenth of every score used to be random_int(30, 90) / 100.
        $this->assertCount(1, array_unique($scores), 'confidence score is not deterministic');
    }

    public function test_a_candidate_whose_name_does_not_match_is_rejected(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'team_id' => $user->current_team_id,
            'child_in_family_id' => null,
        ]);

        $service = $this->withProviders(new SmartMatchingService, [
            'ancestry' => new StubRecordProvider([[
                'id' => 'ANC-2',
                'tree_id' => 'tree-9',
                'first_name' => 'Wolfgang',
                'last_name' => 'Pauli',
            ]]),
        ]);

        $this->assertCount(0, $service->findSmartMatches($user));
        $this->assertDatabaseCount('smart_matches', 0);
    }

    public function test_an_unparseable_provider_date_does_not_throw(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        Person::factory()->create([
            'givn' => 'Ada',
            'surn' => 'Lovelace',
            'team_id' => $user->current_team_id,
            'child_in_family_id' => null,
            'birthday' => '1815-12-10',
        ]);

        $service = $this->withProviders(new SmartMatchingService, [
            'ancestry' => new StubRecordProvider([[
                'id' => 'ANC-3',
                'tree_id' => 'tree-9',
                'first_name' => 'Ada',
                'last_name' => 'Lovelace',
                // Providers return free text here; "abt 1815" is not a date.
                'birth_date' => 'abt 1815',
            ]]),
        ]);

        $this->assertCount(1, $service->findSmartMatches($user));
    }
}

/**
 * A real provider implementation returning fixed candidates — not a mock, so the
 * scoring path under test runs exactly as it does in production.
 */
class StubRecordProvider implements ExternalRecordProviderInterface
{
    public function __construct(private array $candidates) {}

    public function search($localPerson): array
    {
        return $this->candidates;
    }
}
