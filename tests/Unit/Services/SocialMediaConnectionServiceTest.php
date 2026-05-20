<?php

namespace Tests\Unit\Services;

use App\Models\ConnectedAccount;
use App\Models\SocialConnectionPrivacy;
use App\Models\User;
use App\Services\SocialMediaConnectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialMediaConnectionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SocialMediaConnectionService $service;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SocialMediaConnectionService();
    }

    public function testEnableFamilyMatching(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
            'enable_family_matching' => false,
        ]);

        $result = $this->service->enableFamilyMatching($account);

        $this->assertTrue($result);
        $this->assertTrue($account->fresh()->enable_family_matching);
        $this->assertNotNull($account->fresh()->last_synced_at);
    }

    public function testDisableFamilyMatching(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
            'enable_family_matching' => true,
            'cached_profile_data' => ['test' => 'data'],
        ]);

        $result = $this->service->disableFamilyMatching($account);

        $this->assertTrue($result);
        $this->assertFalse($account->fresh()->enable_family_matching);
        $this->assertNull($account->fresh()->cached_profile_data);
        $this->assertNull($account->fresh()->last_synced_at);
    }

    public function testGetOrCreatePrivacySettings(): void
    {
        $user = User::factory()->create();

        $privacy = $this->service->getOrCreatePrivacySettings($user);

        $this->assertInstanceOf(SocialConnectionPrivacy::class, $privacy);
        $this->assertEquals($user->id, $privacy->user_id);
        $this->assertTrue($privacy->allow_family_discovery);
    }

    public function testUpdatePrivacySettings(): void
    {
        $user = User::factory()->create();

        $privacy = $this->service->updatePrivacySettings($user, [
            'allow_family_discovery' => false,
            'share_tree_with_matches' => true,
        ]);

        $this->assertFalse($privacy->allow_family_discovery);
        $this->assertTrue($privacy->share_tree_with_matches);
    }

    public function testNeedsSyncReturnsTrueWhenNeverSynced(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
            'enable_family_matching' => true,
            'last_synced_at' => null,
        ]);

        $result = $this->service->needsSync($account);

        $this->assertTrue($result);
    }

    public function testNeedsSyncReturnsFalseWhenRecentlySynced(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
            'enable_family_matching' => true,
            'last_synced_at' => now()->subHours(2),
        ]);

        $result = $this->service->needsSync($account);

        $this->assertFalse($result);
    }

    public function testNeedsSyncReturnsTrueWhenOldSync(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
            'enable_family_matching' => true,
            'last_synced_at' => now()->subHours(25),
        ]);

        $result = $this->service->needsSync($account);

        $this->assertTrue($result);
    }

    public function testSyncAccountData(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
            'enable_family_matching' => true,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $result = $this->service->syncAccountData($account);

        $this->assertTrue($result);
        $this->assertNotNull($account->fresh()->cached_profile_data);
        $this->assertNotNull($account->fresh()->last_synced_at);
    }

    public function testDisconnectAccount(): void
    {
        $user = User::factory()->create();
        $account = ConnectedAccount::factory()->create([
            'user_id' => $user->id,
        ]);

        $accountId = $account->id;
        $result = $this->service->disconnectAccount($account);

        $this->assertTrue($result);
        $this->assertNull(ConnectedAccount::find($accountId));
    }
}
