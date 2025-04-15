<?php

namespace Tests\Unit\Services;

use App\Models\Team;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Subscription;
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

    public function testCreateTrialSubscription(): void
    {
        $team = Team::factory()->create();
        $this->subscriptionService->createTrialSubscription($team);

        $this->assertTrue($team->subscribed('default'));
        $this->assertTrue($team->subscription('default')->onTrial());
    }

    public function testGetSubscriptionStatus(): void
    {
        $team = Team::factory()->create();
        $this->assertEquals('Inactive', $this->subscriptionService->getSubscriptionStatus($team));

        $this->subscriptionService->createTrialSubscription($team);
        $this->assertEquals('Trial', $this->subscriptionService->getSubscriptionStatus($team));

        // Simulate an active subscription
        $team->subscription('default')->update(['trial_ends_at' => now()->subDay()]);
        $this->assertEquals('Active', $this->subscriptionService->getSubscriptionStatus($team));
    }

    public function testCancelSubscription(): void
    {
        $team = Team::factory()->create();
        $this->subscriptionService->createTrialSubscription($team);
        $this->subscriptionService->cancelSubscription($team);

        $this->assertTrue($team->subscription('default')->cancelled());
    }

    public function testResumeSubscription(): void
    {
        $team = Team::factory()->create();
        $this->subscriptionService->createTrialSubscription($team);
        $this->subscriptionService->cancelSubscription($team);
        $this->subscriptionService->resumeSubscription($team);

        $this->assertFalse($team->subscription('default')->cancelled());
    }
}