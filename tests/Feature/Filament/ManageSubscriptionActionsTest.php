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
 * The remaining premium-dashboard management actions (cancel, resume, downgrade)
 * each delegate to the service seam; the Stripe call is stubbed there. Pause and
 * unpause are covered separately in SubscriptionPauseButtonsTest.
 */
final class ManageSubscriptionActionsTest extends TestCase
{
    use RefreshDatabase;

    private function premiumSubscriber(): User
    {
        config(['premium.enabled' => true]);
        $user = User::factory()->withPersonalTeam()->create(['stripe_id' => 'cus_x']);
        $user->subscriptions()->create([
            'type' => 'premium',
            'stripe_id' => 'sub_x',
            'stripe_status' => 'active',
            'stripe_price' => 'price_premium_monthly',
            'quantity' => 1,
        ]);
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        return $user;
    }

    public function test_the_cancel_button_cancels_via_the_service(): void
    {
        $this->premiumSubscriber();

        $mock = Mockery::mock(SubscriptionService::class)->makePartial();
        $mock->shouldReceive('cancelPremiumSubscription')->once();
        $this->app->instance(SubscriptionService::class, $mock);

        Livewire::test(PremiumDashboardPage::class)->call('cancelSubscription');
    }

    public function test_the_resume_button_resumes_via_the_service(): void
    {
        $this->premiumSubscriber();

        $mock = Mockery::mock(SubscriptionService::class)->makePartial();
        $mock->shouldReceive('resumePremiumSubscription')->once();
        $this->app->instance(SubscriptionService::class, $mock);

        Livewire::test(PremiumDashboardPage::class)->call('resumeSubscription');
    }

    public function test_the_downgrade_button_downgrades_via_the_service(): void
    {
        $this->premiumSubscriber();

        $mock = Mockery::mock(SubscriptionService::class)->makePartial();
        $mock->shouldReceive('downgradeToFree')->once();
        $this->app->instance(SubscriptionService::class, $mock);

        Livewire::test(PremiumDashboardPage::class)->call('downgradeToFree');
    }
}
