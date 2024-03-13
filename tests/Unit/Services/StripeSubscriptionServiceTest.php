&lt;?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\StripeSubscriptionService;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class StripeSubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateSubscriptionSuccess()
    {
        $stripeClientMock = $this->createMock(StripeClient::class);
        $stripeClientMock->method('subscriptions')->willReturn(new class {
            public function update($subscriptionId, $data) {
                return ['id' => $subscriptionId, 'items' => $data['items']];
            }
        });

        $service = new StripeSubscriptionService($stripeClientMock);
        $result = $service->updateSubscription('sub_test', 'price_test');

        $this->assertEquals(['success' => true, 'message' => 'Subscription updated successfully.'], $result);
    }

    public function testUpdateSubscriptionFailure()
    {
        $stripeClientMock = $this->createMock(StripeClient::class);
        $stripeClientMock->method('subscriptions')->willThrowException(new ApiErrorException());

        $service = new StripeSubscriptionService($stripeClientMock);
        $result = $service->updateSubscription('sub_invalid', 'price_invalid');

        $this->assertEquals(['success' => false, 'message' => 'Error updating subscription: '], $result);
    }

    public function testCancelSubscriptionSuccess()
    {
        $stripeClientMock = $this->createMock(StripeClient::class);
        $stripeClientMock->method('subscriptions')->willReturn(new class {
            public function cancel($subscriptionId) {
                return true;
            }
        });

        $service = new StripeSubscriptionService($stripeClientMock);
        $result = $service->cancelSubscription('sub_test');

        $this->assertEquals(['success' => true, 'message' => 'Subscription cancelled successfully.'], $result);
    }

    public function testCancelSubscriptionFailure()
    {
        $stripeClientMock = $this->createMock(StripeClient::class);
        $stripeClientMock->method('subscriptions')->willThrowException(new ApiErrorException());

        $service = new StripeSubscriptionService($stripeClientMock);
        $result = $service->cancelSubscription('sub_invalid');

        $this->assertEquals(['success' => false, 'message' => 'Error cancelling subscription: '], $result);
    }
}
