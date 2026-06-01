<?php

namespace Tests\Feature\Filament;

use App\Filament\App\Pages\SubscriptionPage;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SubscriptionPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_trial_sets_user_to_premium_and_redirects(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(SubscriptionPage::class)
            ->call('startTrial')
            ->assertRedirectContains('premium-dashboard');

        $this->assertTrue($user->fresh()->is_premium);
    }

    public function test_redirect_to_checkout_uses_service_and_config_price(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        config(['subscription.premium.stripe_price_id' => 'page_price_456']);

        $mockCheckout = new class
        {
            public string $url = 'https://stripe-example';
        };

        $pricingInfo = [
            'premium' => [
                'name'           => 'Premium',
                'price'          => '$2.99',
                'interval'       => 'month',
                'trial_days'     => 14,
                'features'       => [],
                'stripe_price_id' => 'page_price_456',
            ],
        ];

        $mockService = \Mockery::mock(SubscriptionService::class);
        $mockService->allows('getPricingInfo')->andReturn($pricingInfo);
        $mockService->allows('checkDnaUploadLimit')->andReturn(['can_upload' => false, 'remaining' => 0, 'limit' => 1]);
        $mockService->allows('getDnaLimitData')->andReturn(['remaining' => 0, 'can_upload' => false, 'limit' => 1]);
        $mockService->shouldReceive('createCheckoutRedirect')
            ->once()
            ->andReturn($mockCheckout);

        $this->app->instance(SubscriptionService::class, $mockService);

        Livewire::actingAs($user)
            ->test(SubscriptionPage::class)
            ->call('redirectToCheckout')
            ->assertRedirect('https://stripe-example');
    }
}
