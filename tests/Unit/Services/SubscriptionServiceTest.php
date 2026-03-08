<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $subscriptionService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = new SubscriptionService();
    }

    public function testCreatePremiumSubscriptionWithoutPaymentMethod(): void
    {
        $user = User::factory()->create();
        $this->subscriptionService->createPremiumSubscription($user);

        $user = $user->fresh();
        $this->assertTrue($user->is_premium);
        $this->assertNotNull($user->trial_ends_at);
        $this->assertNotNull($user->premium_started_at);
        // Trial should be 14 days from now (allow ±1 second tolerance)
        $this->assertEquals(14, now()->diffInDays($user->trial_ends_at));
    }

    public function testGetPricingInfoReturnsPremiumInfo(): void
    {
        $pricingInfo = $this->subscriptionService->getPricingInfo();

        $this->assertArrayHasKey('premium', $pricingInfo);
        $this->assertArrayHasKey('name', $pricingInfo['premium']);
        $this->assertArrayHasKey('features', $pricingInfo['premium']);
        $this->assertEquals('Premium', $pricingInfo['premium']['name']);
        $this->assertIsArray($pricingInfo['premium']['features']);
        // Price should be $2.99
        $this->assertEquals('$2.99', $pricingInfo['premium']['price']);
        // Trial should be 14 days
        $this->assertEquals(14, $pricingInfo['premium']['trial_days']);
    }

    public function testCheckDnaUploadLimitForNonPremiumUser(): void
    {
        $user = User::factory()->create(['is_premium' => false]);
        $result = $this->subscriptionService->checkDnaUploadLimit($user);

        $this->assertArrayHasKey('can_upload', $result);
        $this->assertArrayHasKey('limit', $result);
    }

    public function testGetPremiumFeaturesStatus(): void
    {
        $user = User::factory()->create(['is_premium' => false]);
        $status = $this->subscriptionService->getPremiumFeaturesStatus($user);

        $this->assertIsArray($status);
    }

    public function testDowngradeToFreeClearsTrial(): void
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

    public function testHasExpiredTrialReturnsTrueWhenTrialPassed(): void
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

    public function testHasExpiredTrialReturnsFalseWhenTrialActive(): void
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
