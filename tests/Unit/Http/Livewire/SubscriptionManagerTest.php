&lt;?php

namespace Tests\Unit\Http\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Http\Livewire\SubscriptionManager;
use App\Services\StripeSubscriptionService;
use Mockery;

class SubscriptionManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_method()
    {
        $stripeServiceMock = Mockery::mock(StripeSubscriptionService::class);
        $stripeServiceMock->shouldReceive('getCurrentSubscriptionDetails')->once()->andReturn(['id' => 'sub_test', 'status' => 'active']);
        $stripeServiceMock->shouldReceive('getAvailablePlans')->once()->andReturn(['plan_A' => 'Plan A', 'plan_B' => 'Plan B']);

        Livewire::test(SubscriptionManager::class, ['stripeService' => $stripeServiceMock])
            ->assertSet('subscriptionDetails', ['id' => 'sub_test', 'status' => 'active'])
            ->assertSet('plans', ['plan_A' => 'Plan A', 'plan_B' => 'Plan B']);
    }

    public function test_updateSubscription_method_success()
    {
        $stripeServiceMock = Mockery::mock(StripeSubscriptionService::class);
        $stripeServiceMock->shouldReceive('updateSubscription')->once()->andReturn(['success' => true, 'message' => 'Subscription updated successfully.']);

        Livewire::test(SubscriptionManager::class)
            ->set('selectedPlan', 'plan_A')
            ->call('updateSubscription', $stripeServiceMock)
            ->assertHasNoErrors()
            ->assertEmitted('notify', 'Subscription updated successfully.');
    }

    public function test_updateSubscription_method_failure()
    {
        $stripeServiceMock = Mockery::mock(StripeSubscriptionService::class);
        $stripeServiceMock->shouldReceive('updateSubscription')->once()->andThrow(new \Exception('Error updating subscription'));

        Livewire::test(SubscriptionManager::class)
            ->set('selectedPlan', 'plan_invalid')
            ->call('updateSubscription', $stripeServiceMock)
            ->assertNotEmitted('notify', 'Subscription updated successfully.')
            ->assertEmitted('notify', 'Error updating subscription: Error updating subscription');
    }

    public function test_cancelSubscription_method_success()
    {
        $stripeServiceMock = Mockery::mock(StripeSubscriptionService::class);
        $stripeServiceMock->shouldReceive('cancelSubscription')->once()->andReturn(['success' => true, 'message' => 'Subscription cancelled successfully.']);

        Livewire::test(SubscriptionManager::class)
            ->call('cancelSubscription', $stripeServiceMock)
            ->assertHasNoErrors()
            ->assertEmitted('notify', 'Subscription cancelled successfully.');
    }

    public function test_cancelSubscription_method_failure()
    {
        $stripeServiceMock = Mockery::mock(StripeSubscriptionService::class);
        $stripeServiceMock->shouldReceive('cancelSubscription')->once()->andThrow(new \Exception('Error cancelling subscription'));

        Livewire::test(SubscriptionManager::class)
            ->call('cancelSubscription', $stripeServiceMock)
            ->assertNotEmitted('notify', 'Subscription cancelled successfully.')
            ->assertEmitted('notify', 'Error cancelling subscription: Error cancelling subscription');
    }
}
