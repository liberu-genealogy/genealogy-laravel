<?php

namespace Tests\Unit\Services;

use App\Models\ConnectedAccount;
use App\Models\Person;
use App\Models\SocialConnectionPrivacy;
use App\Models\SocialFamilyConnection;
use App\Models\User;
use App\Services\FamilyMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyMatchingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected FamilyMatchingService $service;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FamilyMatchingService;
    }

    public function test_find_potential_connections_returns_empty_when_privacy_disabled(): void
    {
        $user = User::factory()->create();
        SocialConnectionPrivacy::factory()->create([
            'user_id' => $user->id,
            'allow_family_discovery' => false,
        ]);

        $connections = $this->service->findPotentialConnections($user);

        $this->assertTrue($connections->isEmpty());
    }

    public function test_find_potential_connections_returns_empty_when_no_privacy_settings(): void
    {
        $user = User::factory()->create();

        $connections = $this->service->findPotentialConnections($user);

        $this->assertTrue($connections->isEmpty());
    }

    public function test_create_connection(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
        ]);

        $matchData = [
            'social_id' => 'test123',
            'name' => 'Test Match',
            'email' => 'match@example.com',
            'confidence_score' => 80,
            'common_surnames' => ['Smith', 'Johnson'],
        ];

        $connection = $this->service->createConnection($user, $account, $matchData);

        $this->assertInstanceOf(SocialFamilyConnection::class, $connection);
        $this->assertEquals($user->id, $connection->user_id);
        $this->assertEquals($account->id, $connection->connected_account_id);
        $this->assertEquals('test123', $connection->matched_social_id);
        $this->assertEquals('Test Match', $connection->matched_name);
        $this->assertEquals(80, $connection->confidence_score);
        $this->assertEquals('pending', $connection->status);
    }

    public function test_calculate_confidence_score(): void
    {
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calculateConfidenceScore');

        // Test with 1 common surname (should be 20)
        $score = $method->invoke($this->service, ['Smith']);
        $this->assertEquals(20, $score);

        // Test with 3 common surnames (should be 60)
        $score = $method->invoke($this->service, ['Smith', 'Johnson', 'Williams']);
        $this->assertEquals(60, $score);

        // Test with 6 common surnames (should be capped at 100)
        $score = $method->invoke($this->service, ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller']);
        $this->assertEquals(100, $score);
    }

    public function test_process_matches_returns_zero_when_no_matches(): void
    {
        $user = User::factory()->create();
        SocialConnectionPrivacy::factory()->create([
            'user_id' => $user->id,
            'allow_family_discovery' => true,
        ]);

        $count = $this->service->processMatches($user);

        $this->assertEquals(0, $count);
    }

    /**
     * The happy path: when a real cross-account surname match exists,
     * processMatches must actually create the connection. Guards the
     * account_id tagging in findPotentialConnections — a by-value each()
     * silently dropped it, so this always returned 0.
     */
    public function test_process_matches_creates_a_connection_for_a_real_match(): void
    {
        $searcher = User::factory()->withPersonalTeam()->create();
        SocialConnectionPrivacy::factory()->create([
            'user_id' => $searcher->id,
            'allow_family_discovery' => true,
        ]);
        Person::factory()->create(['team_id' => $searcher->current_team_id, 'surn' => 'Shakespeare']);
        ConnectedAccount::factory()->create([
            'user_id' => $searcher->id,
            'provider' => 'facebook',
            'provider_id' => 'searcher-111',
            'enable_family_matching' => true,
            'cached_profile_data' => ['synced' => true],
        ]);

        // A different user on the same provider sharing a surname, discoverable.
        $relative = User::factory()->withPersonalTeam()->create();
        SocialConnectionPrivacy::factory()->create([
            'user_id' => $relative->id,
            'allow_family_discovery' => true,
        ]);
        Person::factory()->create(['team_id' => $relative->current_team_id, 'surn' => 'Shakespeare']);
        ConnectedAccount::factory()->create([
            'user_id' => $relative->id,
            'provider' => 'facebook',
            'provider_id' => 'relative-999',
            'enable_family_matching' => true,
            'cached_profile_data' => ['synced' => true],
        ]);

        $count = $this->service->processMatches($searcher);

        $this->assertGreaterThan(0, $count, 'processMatches created no connection for a real match');
        $this->assertTrue(
            SocialFamilyConnection::where('user_id', $searcher->id)
                ->where('matched_social_id', 'relative-999')
                ->exists(),
            'No SocialFamilyConnection was created for the matching relative'
        );
    }
}
