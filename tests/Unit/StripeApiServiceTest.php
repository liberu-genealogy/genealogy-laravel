<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\StripeApiService;
use Stripe\StripeClient;
use Stripe\Service\SubscriptionService;
use Stripe\Exception\ApiErrorException;
use Mockery;

class StripeApiServiceTest extends TestCase
{
    public function testUpdateStripeSubscription()
    {
        $subscriptionId = 'sub_123';
        $newPlanId = 'plan_456';

        $stripeClientMock = Mockery::mock(StripeClient::class);
        $stripeClientMock->subscriptions = Mockery::mock(SubscriptionService::class);
        $stripeClientMock->subscriptions->shouldReceive('update')
            ->once()
            ->with($subscriptionId, ['items' => [['id' => $subscriptionId, 'price' => $newPlanId]]])
            ->andReturn(['id' => $subscriptionId, 'items' => [['price' => $newPlanId]]]);

        $service = new StripeApiService($stripeClientMock);
        $result = $service->updateStripeSubscription($subscriptionId, $newPlanId);

        $this->assertIsArray($result);
        $this->assertEquals($newPlanId, $result['items'][0]['price']);
    }

    public function testCancelStripeSubscription()
    {
        $subscriptionId = 'sub_789';

        $stripeClientMock = Mockery::mock(StripeClient::class);
        $stripeClientMock->subscriptions = Mockery::mock(SubscriptionService::class);
        $stripeClientMock->subscriptions->shouldReceive('cancel')
            ->once()
            ->with($subscriptionId)
            ->andReturn(['id' => $subscriptionId, 'status' => 'canceled']);

        $service = new StripeApiService($stripeClientMock);
        $result = $service->cancelStripeSubscription($subscriptionId);

        $this->assertIsArray($result);
        $this->assertEquals('canceled', $result['status']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
