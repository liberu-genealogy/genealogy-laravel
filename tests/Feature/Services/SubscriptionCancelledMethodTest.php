<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Filament\App\Pages\PremiumDashboardPage;
use App\Models\User;
use App\Services\SubscriptionService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription;
use Livewire\Livewire;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Cashier spells the "has been cancelled" check canceled() (one L). Calling
 * cancelled() is a fatal Error, so every test here drives a real Cashier
 * Subscription rather than a mock: a double would happily answer to a method
 * the real class does not have.
 */
class SubscriptionCancelledMethodTest extends TestCase
{
    #[\Override]
    protected function tearDown(): void
    {
        Cashier::useSubscriptionModel(Subscription::class);
        RecordingSubscription::$calls = [];

        parent::tearDown();
    }

    public function test_cashier_subscription_has_no_cancelled_method(): void
    {
        $this->assertTrue(method_exists(Subscription::class, 'canceled'));
        $this->assertFalse(method_exists(Subscription::class, 'cancelled'));
    }

    public function test_cancel_skips_stripe_when_subscription_is_already_cancelled(): void
    {
        $user = $this->premiumUser();
        $this->makeSubscription($user, 'active', now()->addDays(10));

        // canceled() is true, so cancel() must not be called again – reaching Stripe
        // would blow up here, which is itself part of the assertion.
        app(SubscriptionService::class)->cancelPremiumSubscription($user);

        $this->assertFalse($user->fresh()->is_premium);
    }

    public function test_cancel_cancels_a_subscription_that_is_not_yet_cancelled(): void
    {
        Cashier::useSubscriptionModel(RecordingSubscription::class);

        $user = $this->premiumUser();
        $this->makeSubscription($user, 'active', null);

        app(SubscriptionService::class)->cancelPremiumSubscription($user);

        $this->assertSame(['cancel'], RecordingSubscription::$calls);
        $this->assertFalse($user->fresh()->is_premium);
    }

    public function test_resume_is_skipped_once_the_grace_period_has_expired(): void
    {
        $user = $this->premiumUser(['is_premium' => false]);
        $subscription = $this->makeSubscription($user, 'canceled', now()->subDay());

        // canceled() would be true here; Cashier's resume() throws a LogicException
        // outside the grace period, so the guard must reject this subscription.
        app(SubscriptionService::class)->resumePremiumSubscription($user);

        $this->assertFalse($user->fresh()->is_premium);
        $this->assertNotNull($subscription->fresh()->ends_at);
    }

    public function test_resume_restores_premium_within_the_grace_period(): void
    {
        Cashier::useSubscriptionModel(RecordingSubscription::class);

        $user = $this->premiumUser(['is_premium' => false]);
        $subscription = $this->makeSubscription($user, 'active', now()->addDays(10));

        app(SubscriptionService::class)->resumePremiumSubscription($user);

        $this->assertSame(['resume'], RecordingSubscription::$calls);
        $this->assertTrue($user->fresh()->is_premium);
        $this->assertNull($subscription->fresh()->ends_at);
    }

    /**
     * The header-action tests above reach the page by reflection because the blade
     * could not render at all: it built links with route('filament.app.resources.
     * duplicate-checks.index') and friends, which are tenant-scoped routes, with no
     * tenant parameter and nothing supplying a URL::defaults() — so it threw
     * UrlGenerationException on every render, in production too. The links now go
     * through Resource::getUrl(), which injects the tenant, so the page can actually
     * be mounted; this is the coverage that was impossible before.
     */
    public function test_premium_dashboard_page_renders(): void
    {
        $user = $this->actingUser();
        $this->makeSubscription($user, 'active', now()->addDays(10));

        Livewire::test(PremiumDashboardPage::class)->assertOk();
    }

    public function test_dashboard_offers_resume_within_the_grace_period(): void
    {
        $user = $this->actingUser();
        $this->makeSubscription($user, 'active', now()->addDays(10));

        $this->assertSame(['resume', 'downgrade'], $this->headerActionNames());
    }

    public function test_dashboard_offers_cancel_once_the_grace_period_has_expired(): void
    {
        $user = $this->actingUser();
        $this->makeSubscription($user, 'canceled', now()->subDay());

        // An ended subscription can no longer be resumed, so Resume must not be
        // offered – Cashier's resume() would only throw.
        $this->assertSame(['cancel', 'downgrade'], $this->headerActionNames());
    }

    public function test_dashboard_offers_cancel_when_there_is_no_subscription(): void
    {
        $this->actingUser();

        $this->assertSame(['cancel', 'downgrade'], $this->headerActionNames());
    }

    /**
     * Calls the page's header actions directly rather than rendering it: the
     * premium-dashboard blade generates tenant routes via the bare route() helper
     * without a tenant parameter, which fatals independently of this guard.
     *
     * @return list<string>
     */
    private function headerActionNames(): array
    {
        $method = new ReflectionMethod(PremiumDashboardPage::class, 'getHeaderActions');

        return array_map(
            fn (Action $action): string => $action->getName(),
            $method->invoke(new PremiumDashboardPage),
        );
    }

    private function premiumUser(array $attributes = []): User
    {
        return User::factory()->withPersonalTeam()->create($attributes + ['is_premium' => true]);
    }

    private function actingUser(): User
    {
        $user = $this->premiumUser();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    private function makeSubscription(User $user, string $stripeStatus, ?Carbon $endsAt): Subscription
    {
        return Subscription::create([
            'user_id' => $user->id,
            'type' => 'premium',
            'stripe_id' => 'sub_'.$user->id.'_'.$stripeStatus,
            'stripe_status' => $stripeStatus,
            'stripe_price' => 'price_premium_monthly',
            'quantity' => 1,
            'ends_at' => $endsAt,
        ]);
    }
}

/**
 * Records cancel()/resume() instead of calling Stripe, while inheriting the real
 * canceled()/onGracePeriod() predicates that are under test. The service reloads
 * the subscription from the database, so the log has to be static.
 */
class RecordingSubscription extends Subscription
{
    /** @var list<string> */
    public static array $calls = [];

    protected $table = 'subscriptions';

    /**
     * Without this, Eloquent derives the items() foreign key from the subclass
     * name and queries subscription_items.recording_subscription_id.
     */
    #[\Override]
    public function getForeignKey(): string
    {
        return 'subscription_id';
    }

    #[\Override]
    public function cancel()
    {
        static::$calls[] = 'cancel';

        return $this;
    }

    #[\Override]
    public function resume()
    {
        static::$calls[] = 'resume';

        $this->forceFill(['stripe_status' => 'active', 'ends_at' => null])->save();

        return $this;
    }
}
