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
        $this->service = new FamilyMatchingService();
    }

    public function testFindPotentialConnectionsReturnsEmptyWhenPrivacyDisabled(): void
    {
        $user = User::factory()->create();
        SocialConnectionPrivacy::factory()->create([
            'user_id' => $user->id,
            'allow_family_discovery' => false,
        ]);

        $connections = $this->service->findPotentialConnections($user);

        $this->assertTrue($connections->isEmpty());
    }

    public function testFindPotentialConnectionsReturnsEmptyWhenNoPrivacySettings(): void
    {
        $user = User::factory()->create();

        $connections = $this->service->findPotentialConnections($user);

        $this->assertTrue($connections->isEmpty());
    }

    public function testCreateConnection(): void
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

    public function testCalculateConfidenceScore(): void
    {
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calculateConfidenceScore');
        $method->setAccessible(true);

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

    public function testProcessMatchesReturnsZeroWhenNoMatches(): void
    {
        $user = User::factory()->create();
        SocialConnectionPrivacy::factory()->create([
            'user_id' => $user->id,
            'allow_family_discovery' => true,
        ]);

        $count = $this->service->processMatches($user);

        $this->assertEquals(0, $count);
    }
}
