<?php

namespace Tests\Feature\Filament;

use App\Filament\App\Pages\SubscriptionPage;
use App\Models\User;
use App\Services\SubscriptionService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SubscriptionPageTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        return $user;
    }

    public function test_start_trial_sets_user_to_premium_and_redirects(): void
    {
        // The no-card trial only exists when the deployment doesn't require a card.
        config()->set('subscription.premium.require_card', false);
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(SubscriptionPage::class)
            ->call('startTrial')
            ->assertRedirectContains('premium-dashboard');

        $this->assertTrue($user->fresh()->is_premium);
    }

    public function test_trial_button_hidden_when_card_required(): void
    {
        config()->set('subscription.premium.require_card', true);
        $this->actingUser();

        Livewire::test(SubscriptionPage::class)
            ->assertSee('Subscribe')
            ->assertDontSee('Start Free Trial');
    }

    public function test_start_trial_rejected_server_side_when_card_required(): void
    {
        config()->set('subscription.premium.require_card', true);
        $user = $this->actingUser();

        Livewire::test(SubscriptionPage::class)->call('startTrial');

        $this->assertFalse((bool) $user->fresh()->is_premium, 'no premium granted without a card');
    }

    public function test_no_trial_button_when_trial_days_zero(): void
    {
        config()->set('subscription.premium.require_card', false);
        config()->set('subscription.premium.trial_days', 0);
        $this->actingUser();

        Livewire::test(SubscriptionPage::class)
            ->assertDontSee('Start Free Trial');
    }

    public function test_redirect_to_checkout_uses_service_for_selected_interval(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $mockCheckout = new class
        {
            public string $url = 'https://stripe-example';
        };

        $pricingInfo = [
            'premium' => [
                'name' => 'Premium',
                'trial_days' => 14,
                'require_card' => true,
                'intervals' => [
                    'month' => ['interval' => 'month', 'amount' => 299, 'price' => '$2.99'],
                    'year' => ['interval' => 'year', 'amount' => 2999, 'price' => '$29.99'],
                ],
                'features' => [],
            ],
        ];

        $mockService = \Mockery::mock(SubscriptionService::class);
        $mockService->allows('getPricingInfo')->andReturn($pricingInfo);
        $mockService->allows('requiresCard')->andReturnTrue();
        $mockService->allows('trialDays')->andReturn(14);
        $mockService->allows('checkDnaUploadLimit')->andReturn(['can_upload' => false, 'remaining' => 0, 'limit' => 1]);
        $mockService->shouldReceive('createCheckoutRedirect')
            ->once()
            ->with(\Mockery::type(User::class), 'year')
            ->andReturn($mockCheckout);

        $this->app->instance(SubscriptionService::class, $mockService);

        Livewire::actingAs($user)
            ->test(SubscriptionPage::class)
            ->set('interval', 'year')
            ->call('redirectToCheckout')
            ->assertRedirect('https://stripe-example');
    }
}
