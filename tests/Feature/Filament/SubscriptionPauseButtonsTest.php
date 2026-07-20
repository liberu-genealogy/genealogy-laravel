<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\App\Pages\PremiumDashboardPage;
use App\Models\User;
use App\Services\SubscriptionService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

/**
 * A subscriber pauses/resumes from the premium dashboard. The Stripe call is
 * stubbed at the service seam (it would hit the Stripe API); the buttons only
 * need to delegate to it.
 */
final class SubscriptionPauseButtonsTest extends TestCase
{
    use RefreshDatabase;

    private function premiumUser(bool $paused): User
    {
        config(['premium.enabled' => true]);
        $user = User::factory()->withPersonalTeam()->create(['stripe_id' => 'cus_x']);
        $user->subscriptions()->create([
            'type' => 'premium',
            'stripe_id' => 'sub_x',
            'stripe_status' => 'active',
            'stripe_price' => 'price_premium_monthly',
            'quantity' => 1,
            'paused_at' => $paused ? now() : null,
        ]);
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        return $user;
    }

    public function test_the_pause_button_pauses_via_the_service(): void
    {
        $this->premiumUser(paused: false);

        $mock = Mockery::mock(SubscriptionService::class)->makePartial();
        $mock->shouldReceive('pausePremiumSubscription')->once();
        $this->app->instance(SubscriptionService::class, $mock);

        Livewire::test(PremiumDashboardPage::class)->call('pauseSubscription');
    }

    public function test_the_resume_button_unpauses_via_the_service(): void
    {
        $this->premiumUser(paused: true);

        $mock = Mockery::mock(SubscriptionService::class)->makePartial();
        $mock->shouldReceive('unpausePremiumSubscription')->once();
        $this->app->instance(SubscriptionService::class, $mock);

        Livewire::test(PremiumDashboardPage::class)->call('unpauseSubscription');
    }
}
