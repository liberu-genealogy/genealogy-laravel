<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SubscriptionService $subscriptionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = new SubscriptionService;
    }

    public function test_create_premium_subscription_without_payment_method(): void
    {
        $user = User::factory()->create();
        $this->subscriptionService->createPremiumSubscription($user);

        $user = $user->fresh();
        $this->assertTrue($user->is_premium);
        $this->assertNotNull($user->trial_ends_at);
        $this->assertNotNull($user->premium_started_at);
        // Trial should be 14 days from now (allow ±1 second tolerance)
        $this->assertEqualsWithDelta(14, now()->diffInDays($user->trial_ends_at), 0.01);
    }

    public function test_get_pricing_info_returns_premium_info(): void
    {
        $pricingInfo = $this->subscriptionService->getPricingInfo();

        $this->assertArrayHasKey('premium', $pricingInfo);
        $this->assertArrayHasKey('name', $pricingInfo['premium']);
        $this->assertArrayHasKey('features', $pricingInfo['premium']);
        $this->assertEquals('Premium', $pricingInfo['premium']['name']);
        $this->assertIsArray($pricingInfo['premium']['features']);
    }

    public function test_check_dna_upload_limit_for_non_premium_user(): void
    {
        $user = User::factory()->create(['is_premium' => false]);
        $result = $this->subscriptionService->checkDnaUploadLimit($user);

        $this->assertArrayHasKey('can_upload', $result);
        $this->assertArrayHasKey('limit', $result);
    }

    public function test_get_premium_features_status(): void
    {
        $user = User::factory()->create(['is_premium' => false]);
        $status = $this->subscriptionService->getPremiumFeaturesStatus($user);

        $this->assertIsArray($status);
    }

    public function test_downgrade_to_free_clears_trial(): void
    {
        $user = User::factory()->create([
            'is_premium' => true,
            'trial_ends_at' => now()->addDays(5),
        ]);

        $this->subscriptionService->downgradeToFree($user);

        $user = $user->fresh();
        $this->assertFalse($user->is_premium);
        $this->assertNull($user->trial_ends_at);
    }

    public function test_has_expired_trial_returns_true_when_trial_passed(): void
    {
        // Disable the global premium bypass for this test
        config(['premium.enabled' => false]);

        $user = User::factory()->create([
            'is_premium' => true,
            'trial_ends_at' => now()->subDay(),
        ]);

        $this->assertTrue($user->hasExpiredTrial());
        $this->assertFalse($user->isPremium());
    }

    public function test_has_expired_trial_returns_false_when_trial_active(): void
    {
        config(['premium.enabled' => false]);

        $user = User::factory()->create([
            'is_premium' => true,
            'trial_ends_at' => now()->addDays(10),
        ]);

        $this->assertFalse($user->hasExpiredTrial());
        $this->assertTrue($user->isPremium());
    }
}
