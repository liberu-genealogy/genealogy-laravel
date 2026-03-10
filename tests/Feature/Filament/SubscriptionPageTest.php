<?php

namespace Tests\Feature\Filament;

use App\Filament\App\Pages\SubscriptionPage;
use App\Services\SubscriptionService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SubscriptionPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_trial_sets_user_to_premium_and_redirects()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(SubscriptionPage::class)
            ->call('startTrial')
            ->assertRedirect(route('filament.app.pages.premium-dashboard'));

        $this->assertTrue($user->fresh()->is_premium);
    }

    public function test_redirect_to_checkout_uses_service_and_config_price()
    {
        $user = User::factory()->create();
        config(['subscription.premium.stripe_price_id' => 'page_price_456']);

        $mockBuilder = new class {
            public function trialDays($days) { return $this; }
            public function checkout($data) { return redirect('https://stripe-example'); }
        };

        $user = \Mockery::mock($user)->makePartial();
        $user->shouldReceive('newSubscription')
            ->once()
            ->with('premium', 'page_price_456')
            ->andReturn($mockBuilder);

        Livewire::actingAs($user)
            ->test(SubscriptionPage::class)
            ->call('redirectToCheckout')
            ->assertRedirect('https://stripe-example');
    }
}
