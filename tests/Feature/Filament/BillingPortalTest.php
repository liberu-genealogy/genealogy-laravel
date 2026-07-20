<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\App\Pages\PremiumDashboardPage;
use App\Models\User;
use App\Services\SubscriptionService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

/**
 * A subscriber manages their card and views invoices in Stripe's hosted Billing
 * portal (ADR 0001), reached from the premium dashboard. This asserts the action
 * hands off to the portal; the actual portal is Stripe's, so the Cashier call is
 * stubbed at the service seam (mirroring the checkout test).
 */
final class BillingPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_manage_billing_redirects_to_the_stripe_billing_portal(): void
    {
        // premium.enabled short-circuits the dashboard's mount() redirects so the
        // page renders; stripe_id makes the user a real Stripe customer.
        config(['premium.enabled' => true]);
        $user = User::factory()->withPersonalTeam()->create(['stripe_id' => 'cus_test']);
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        $mock = Mockery::mock(SubscriptionService::class)->makePartial();
        $mock->shouldReceive('createBillingPortalRedirect')
            ->once()
            ->andReturn(new RedirectResponse('https://billing.stripe.test/session'));
        $this->app->instance(SubscriptionService::class, $mock);

        Livewire::actingAs($user)
            ->test(PremiumDashboardPage::class)
            ->call('manageBilling')
            ->assertRedirect('https://billing.stripe.test/session');
    }
}
