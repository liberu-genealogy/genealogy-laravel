&lt;?php

namespace Tests\Unit\Filament\Pages;

use PHPUnit\Framework\TestCase;
use App\Filament\Pages\ManageSubscription;
use App\Services\StripeSubscriptionService;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_method()
    {
        $stripeServiceMock = $this->createMock(StripeSubscriptionService::class);
        $stripeServiceMock->method('getCurrentSubscriptionDetails')->willReturn(['id' => 'sub_123', 'status' => 'active']);
        $stripeServiceMock->method('getAvailablePlans')->willReturn(['plan_basic' => 'Basic Plan', 'plan_premium' => 'Premium Plan']);

        Livewire::test(ManageSubscription::class, ['stripeService' => $stripeServiceMock])
            ->assertSet('subscriptionDetails', ['id' => 'sub_123', 'status' => 'active'])
            ->assertSet('plans', ['plan_basic' => 'Basic Plan', 'plan_premium' => 'Premium Plan']);
    }

    public function test_updateSubscription_method_success()
    {
        $stripeServiceMock = $this->createMock(StripeSubscriptionService::class);
        $stripeServiceMock->expects($this->once())->method('updateSubscription')->willReturn(['success' => true, 'message' => 'Subscription updated successfully.']);

        Livewire::test(ManageSubscription::class, ['stripeService' => $stripeServiceMock])
            ->call('updateSubscription')
            ->assertSet('subscriptionDetails', ['success' => true, 'message' => 'Subscription updated successfully.'])
            ->assertEmitted('notify', 'success', 'Subscription updated successfully.');
    }

    public function test_updateSubscription_method_failure()
    {
        $stripeServiceMock = $this->createMock(StripeSubscriptionService::class);
        $stripeServiceMock->method('updateSubscription')->willThrowException(new \Exception('Error updating subscription'));

        Livewire::test(ManageSubscription::class, ['stripeService' => $stripeServiceMock])
            ->call('updateSubscription')
            ->assertNotEmitted('notify', 'success')
            ->assertEmitted('notify', 'error', 'Error updating subscription');
    }

    public function test_cancelSubscription_method_success()
    {
        $stripeServiceMock = $this->createMock(StripeSubscriptionService::class);
        $stripeServiceMock->expects($this->once())->method('cancelSubscription')->willReturn(['success' => true, 'message' => 'Subscription cancelled successfully.']);

        Livewire::test(ManageSubscription::class, ['stripeService' => $stripeServiceMock])
            ->call('cancelSubscription')
            ->assertSet('subscriptionDetails', null)
            ->assertEmitted('notify', 'success', 'Subscription cancelled successfully.');
    }

    public function test_cancelSubscription_method_failure()
    {
        $stripeServiceMock = $this->createMock(StripeSubscriptionService::class);
        $stripeServiceMock->method('cancelSubscription')->willThrowException(new \Exception('Error cancelling subscription'));

        Livewire::test(ManageSubscription::class, ['stripeService' => $stripeServiceMock])
            ->call('cancelSubscription')
            ->assertNotEmitted('notify', 'success')
            ->assertEmitted('notify', 'error', 'Error cancelling subscription');
    }
}
