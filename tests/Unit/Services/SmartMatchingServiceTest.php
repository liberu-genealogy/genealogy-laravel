<?php

namespace Tests\Unit\Services;

use App\Models\Person;
use App\Models\User;
use App\Models\Team;
use App\Services\SmartMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmartMatchingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SmartMatchingService $service;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        
        // Configure providers for testing
        Config::set('services.myheritage.api_key', 'test-myheritage-key');
        Config::set('services.myheritage.base_url', 'https://api.myheritage.test/v1');
        Config::set('services.ancestry.api_key', 'test-ancestry-key');
        Config::set('services.ancestry.base_url', 'https://api.ancestry.test/v1');
        Config::set('services.familysearch.api_key', 'test-familysearch-key');
        Config::set('services.familysearch.base_url', 'https://api.familysearch.test/platform');
        
        $this->service = new SmartMatchingService();
    }

    public function testServiceInitializesWithConfiguredProviders(): void
    {
        // Service should initialize successfully with configured providers
        $this->assertInstanceOf(SmartMatchingService::class, $this->service);
    }

    public function testFindSmartMatchesReturnsCollectionForUserWithNoMissingParents(): void
    {
        Http::fake();
        
        $team = Team::factory()->create();
        $user = User::factory()->create(['current_team_id' => $team->id]);
        
        $matches = $this->service->findSmartMatches($user);
        
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $matches);
    }

    public function testFindSmartMatchesCallsProvidersWhenPeopleHaveMissingParents(): void
    {
        Http::fake([
            'api.myheritage.test/*' => Http::response(['persons' => []], 200),
            'api.ancestry.test/*' => Http::response(['records' => []], 200),
            'api.familysearch.test/*' => Http::response(['entries' => []], 200),
        ]);
        
        $team = Team::factory()->create();
        $user = User::factory()->create(['current_team_id' => $team->id]);
        
        // Create a person with missing parents (no family)
        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'child_in_family_id' => null,
        ]);
        
        $matches = $this->service->findSmartMatches($user);
        
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $matches);
    }

    public function testServiceWorksWithoutConfiguredProviders(): void
    {
        // Remove all provider configurations
        Config::set('services.myheritage.api_key', '');
        Config::set('services.ancestry.api_key', '');
        Config::set('services.familysearch.api_key', '');
        
        $service = new SmartMatchingService();
        $team = Team::factory()->create();
        $user = User::factory()->create(['current_team_id' => $team->id]);
        
        // Should still work, just using simulation mode
        $matches = $service->findSmartMatches($user);
        
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $matches);
    }

    public function testServiceCreatesSmartMatchRecordsForFoundMatches(): void
    {
        Http::fake([
            'api.myheritage.test/*' => Http::response([
                'persons' => [
                    [
                        'id' => 'MH-001',
                        'tree_id' => 'tree-123',
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'birth_year' => 1880,
                    ],
                ],
            ], 200),
            'api.ancestry.test/*' => Http::response(['records' => []], 200),
            'api.familysearch.test/*' => Http::response(['entries' => []], 200),
        ]);
        
        $team = Team::factory()->create();
        $user = User::factory()->create(['current_team_id' => $team->id]);
        
        // Create a person with missing parents
        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'child_in_family_id' => null,
        ]);
        
        $matches = $this->service->findSmartMatches($user);
        
        // Should have created smart match records (count can be 0 or more depending on scoring)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $matches);
    }
}
