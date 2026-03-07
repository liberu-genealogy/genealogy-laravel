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

        $this->assertTrue($user->fresh()->is_premium);
        $this->assertNotNull($user->fresh()->trial_ends_at);
        $this->assertNotNull($user->fresh()->premium_started_at);
    }

    public function testGetPricingInfoReturnsPremiumInfo(): void
    {
        $pricingInfo = $this->subscriptionService->getPricingInfo();

        $this->assertArrayHasKey('premium', $pricingInfo);
        $this->assertArrayHasKey('name', $pricingInfo['premium']);
        $this->assertArrayHasKey('features', $pricingInfo['premium']);
        $this->assertEquals('Premium', $pricingInfo['premium']['name']);
        $this->assertIsArray($pricingInfo['premium']['features']);
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
}