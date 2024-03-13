<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\Services\StripeSubscriptionService;
use App\Services\DatabaseUpdateService;
use App\Services\StripeApiService;

class StripeSubscriptionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseUpdateServiceMock = Mockery::mock(DatabaseUpdateService::class);
        $this->stripeApiServiceMock = Mockery::mock(StripeApiService::class);
        $this->stripeSubscriptionService = new StripeSubscriptionService($this->stripeApiServiceMock, $this->databaseUpdateServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testUpdateSubscriptionSuccess()
    {
        $subscriptionId = 'sub_123';
        $newPlanId = 'plan_456';
        $this->stripeApiServiceMock->shouldReceive('updateStripeSubscription')->once()->andReturn(['success' => true]);
        $this->databaseUpdateServiceMock->shouldReceive('updateSubscriptionRecord')->once()->andReturn(['success' => true, 'message' => 'Subscription updated successfully.']);

        $result = $this->stripeSubscriptionService->updateSubscription($subscriptionId, $newPlanId);

        $this->assertEquals(['success' => true, 'message' => 'Subscription updated successfully.'], $result);
    }

    public function testUpdateSubscriptionStripeApiFailure()
    {
        $subscriptionId = 'sub_123';
        $newPlanId = 'plan_456';
        $this->stripeApiServiceMock->shouldReceive('updateStripeSubscription')->once()->andReturn(['success' => false, 'message' => 'Error updating subscription with Stripe.']);

        $result = $this->stripeSubscriptionService->updateSubscription($subscriptionId, $newPlanId);

        $this->assertEquals(['success' => false, 'message' => 'Error updating subscription with Stripe.'], $result);
    }

    public function testCancelSubscriptionSuccess()
    {
        $subscriptionId = 'sub_789';
        $this->stripeApiServiceMock->shouldReceive('cancelStripeSubscription')->once()->andReturn(['success' => true]);
        $this->databaseUpdateServiceMock->shouldReceive('cancelSubscriptionRecord')->once()->andReturn(['success' => true, 'message' => 'Subscription cancelled successfully.']);

        $result = $this->stripeSubscriptionService->cancelSubscription($subscriptionId);

        $this->assertEquals(['success' => true, 'message' => 'Subscription cancelled successfully.'], $result);
    }

    public function testCancelSubscriptionStripeApiFailure()
    {
        $subscriptionId = 'sub_789';
        $this->stripeApiServiceMock->shouldReceive('cancelStripeSubscription')->once()->andReturn(['success' => false, 'message' => 'Error cancelling subscription with Stripe.']);

        $result = $this->stripeSubscriptionService->cancelSubscription($subscriptionId);

        $this->assertEquals(['success' => false, 'message' => 'Error cancelling subscription with Stripe.'], $result);
    }
}
