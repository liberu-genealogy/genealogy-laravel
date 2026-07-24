<?php

namespace App\Services;

use App\Models\SubscriptionPrice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\SubscriptionBuilder;
use RuntimeException;

class SubscriptionService
{
    /** The billing intervals the premium tier is offered at. */
    public const INTERVALS = ['month', 'year'];

    /**
     * Whether a card must be taken before premium access is granted. When true,
     * the no-card local-trial path is unavailable (issue #1614). Defaults on.
     */
    public function requiresCard(): bool
    {
        return (bool) config('subscription.premium.require_card', true);
    }

    /** Configured trial length in days; zero means no trial (immediate charge). */
    public function trialDays(): int
    {
        return (int) config('subscription.premium.trial_days', 14);
    }

    /**
     * Trial days to apply at checkout, or null to apply none. A configured length
     * of zero yields null so Cashier never stamps a trial and Stripe charges
     * immediately.
     */
    public function checkoutTrialDays(): ?int
    {
        $days = $this->trialDays();

        return $days > 0 ? $days : null;
    }

    /**
     * Create premium subscription.
     *
     * With no payment method this is the no-card local trial — granted only when
     * the deployment does not require a card. With a payment method, defer to
     * Cashier to create a real Stripe subscription on the managed monthly price.
     */
    public function createPremiumSubscription(User $user, ?string $paymentMethod = null, string $interval = 'month')
    {
        // No-card local trial.
        if (in_array($paymentMethod, [null, '', '0'], true)) {
            if ($this->requiresCard()) {
                // Server-side defense: the UI hides this path, but the Livewire
                // action must not grant premium without a card either.
                throw new RuntimeException('A payment method is required; the no-card trial is disabled.');
            }

            $user->forceFill([
                'is_premium' => true,
                'premium_started_at' => now(),
                // Generic trial used by Cashier's Billable::onTrial()
                'trial_ends_at' => now()->addDays($this->trialDays()),
            ])->save();

            return null;
        }

        $subscriptionBuilder = $this->applyTrial(
            $user->newSubscription('premium', $this->resolveManagedPrice($interval))
        );

        $subscription = $subscriptionBuilder->create($paymentMethod);

        // mark the user locally as premium; Cashier will also update stripe_id, etc.
        $user->update([
            'is_premium' => true,
            'premium_started_at' => now(),
        ]);

        return $subscription;
    }

    /**
     * Resolve the Stripe price id for a billing interval, creating and owning the
     * Stripe Product/Price ourselves so no Dashboard setup is required (managed
     * prices — ADR 0003). The (interval, livemode) record is reused across
     * requests; because Stripe Prices are immutable, a changed configured amount
     * auto-heals: a fresh Price is created, the stale one archived, record updated.
     */
    public function resolveManagedPrice(string $interval): string
    {
        $amount = $this->amountFor($interval);
        $currency = $this->currency();
        $livemode = $this->livemode();

        $record = SubscriptionPrice::query()
            ->where('interval', $interval)
            ->where('livemode', $livemode)
            ->first();

        if ($record && $record->unit_amount === $amount && $record->currency === $currency) {
            return $record->stripe_price_id;
        }

        // Reuse the Product across price changes; only the Price is recreated.
        $productId = $record ? $record->stripe_product_id : $this->createStripeProduct($this->productName());
        $priceId = $this->createStripePrice($productId, $amount, $currency, $interval);

        if ($record) {
            // Stripe Prices are immutable, so the superseded one is archived.
            $this->archiveStripePrice($record->stripe_price_id);
        }

        SubscriptionPrice::updateOrCreate(
            ['interval' => $interval, 'livemode' => $livemode],
            [
                'stripe_product_id' => $productId,
                'stripe_price_id' => $priceId,
                'unit_amount' => $amount,
                'currency' => $currency,
            ],
        );

        return $priceId;
    }

    /**
     * Cancel premium subscription
     */
    public function cancelPremiumSubscription(User $user): void
    {
        $subscription = $user->subscription('premium');

        // Only call cancel() on Stripe if the subscription isn't already cancelled –
        // preventing a redundant API call. Regardless of subscription state, always
        // clear the is_premium flag so the user's access is revoked.
        if ($subscription && ! $subscription->canceled()) {
            $subscription->cancel();
        }

        $user->update([
            'is_premium' => false,
        ]);
    }

    /**
     * Downgrade to free plan – removes premium flag and cancels any active subscription
     * while preserving all core free-tier features.
     */
    public function downgradeToFree(User $user): void
    {
        $this->cancelPremiumSubscription($user);

        // Clear trial so the user is not considered on trial anymore
        $user->forceFill([
            'trial_ends_at' => null,
        ])->save();
    }

    /**
     * Resume premium subscription
     */
    public function resumePremiumSubscription(User $user): void
    {
        $subscription = $user->subscription('premium');

        // Cashier's resume() throws a LogicException unless the subscription is still
        // within its grace period, so guard on onGracePeriod() rather than canceled():
        // a cancelled subscription whose grace period has expired can never be resumed.
        if ($subscription && $subscription->onGracePeriod()) {
            $subscription->resume();

            $user->update([
                'is_premium' => true,
            ]);
        }
    }

    /**
     * Get subscription pricing information (display-only). Prices are derived from
     * the configured amounts so the shown price can never drift from the charge.
     */
    public function getPricingInfo(): array
    {
        $intervals = [];
        foreach (self::INTERVALS as $interval) {
            $intervals[$interval] = [
                'interval' => $interval,
                'amount' => $this->amountFor($interval),
                'price' => $this->formatPrice($interval),
            ];
        }

        return [
            'premium' => [
                'name' => 'Premium',
                'trial_days' => $this->trialDays(),
                'require_card' => $this->requiresCard(),
                'intervals' => $intervals,
                'features' => [
                    'Premium user badge',
                    'Unlimited DNA kit uploads',
                    'Duplicate person checker',
                    'Smart matching with public trees',
                    'Priority support',
                    'Advanced charts and reports',
                ],
            ],
        ];
    }

    /**
     * Build a Stripe Checkout session redirect response for the chosen billing
     * interval. Used by the Filament page to send the user to Stripe's hosted
     * checkout form.
     */
    public function createCheckoutRedirect($user, string $interval = 'month')
    {
        $builder = $this->applyTrial(
            $user->newSubscription('premium', $this->resolveManagedPrice($interval))
        );

        return $builder->checkout([
            'success_url' => route('filament.app.pages.premium-dashboard', ['tenant' => $user->currentTeam]),
            'cancel_url' => route('filament.app.pages.subscription', ['tenant' => $user->currentTeam]),
        ]);
    }

    /**
     * Apply the configured trial to a subscription builder, or none when the
     * trial length is zero (immediate charge).
     */
    private function applyTrial(SubscriptionBuilder $builder): SubscriptionBuilder
    {
        if (($trialDays = $this->checkoutTrialDays()) !== null) {
            $builder->trialDays($trialDays);
        }

        return $builder;
    }

    /**
     * Pause the user's premium subscription (Stripe pause_collection). Billing
     * stops and premium access is revoked while paused (ADR 0002). The local
     * paused_at marker is set here for immediate effect; the webhook keeps it in
     * sync if the pause is also changed from Stripe's side.
     */
    public function pausePremiumSubscription(User $user): void
    {
        $subscription = $user->subscription('premium');

        if ($subscription && $subscription->paused_at === null) {
            $subscription->updateStripeSubscription(['pause_collection' => ['behavior' => 'void']]);
            $subscription->update(['paused_at' => now()]);
        }
    }

    /**
     * Resume a paused subscription: clear Stripe's pause_collection and the local
     * marker, restoring billing and premium access.
     */
    public function unpausePremiumSubscription(User $user): void
    {
        $subscription = $user->subscription('premium');

        if ($subscription && $subscription->paused_at !== null) {
            $subscription->updateStripeSubscription(['pause_collection' => '']);
            $subscription->update(['paused_at' => null]);
        }
    }

    /**
     * Redirect the user to Stripe's hosted Billing portal to manage their card,
     * view invoices, and cancel (ADR 0001 — the app does not render these). Only
     * meaningful for a user who is already a Stripe customer.
     */
    public function createBillingPortalRedirect(User $user): RedirectResponse
    {
        return $user->redirectToBillingPortal(
            route('filament.app.pages.premium-dashboard')
        );
    }

    /**
     * Check if user has reached DNA upload limit
     */
    public function checkDnaUploadLimit(User $user): array
    {
        if ($user->isPremium()) {
            return [
                'can_upload' => true,
                'remaining' => 'unlimited',
                'limit' => 'unlimited',
            ];
        }

        $remaining = max(0, 1 - $user->dna_uploads_count);

        return [
            'can_upload' => $remaining > 0,
            'remaining' => $remaining,
            'limit' => 1,
        ];
    }

    /**
     * Get premium features status for user
     */
    public function getPremiumFeaturesStatus(User $user): array
    {
        $isPremium = $user->isPremium();

        return [
            'is_premium' => $isPremium,
            'on_trial' => $user->onPremiumTrial(),
            'trial_days_remaining' => $user->trialDaysRemaining(),
            'features' => [
                'premium_badge' => $isPremium,
                'unlimited_dna' => $isPremium,
                'duplicate_checker' => $isPremium,
                'smart_matching' => $isPremium,
                'priority_support' => $isPremium,
                'advanced_charts' => $isPremium,
            ],
        ];
    }

    /** Configured amount (minor units) for a billing interval. */
    public function amountFor(string $interval): int
    {
        $this->assertInterval($interval);

        return (int) config("subscription.premium.amounts.{$interval}");
    }

    /** Human-readable price string derived from amount + currency. */
    public function formatPrice(string $interval): string
    {
        return Cashier::formatAmount($this->amountFor($interval), $this->currency());
    }

    private function assertInterval(string $interval): void
    {
        if (! in_array($interval, self::INTERVALS, true)) {
            throw new InvalidArgumentException("Unsupported billing interval: {$interval}");
        }
    }

    private function currency(): string
    {
        return strtolower((string) config('cashier.currency', 'usd'));
    }

    private function productName(): string
    {
        return (string) config('subscription.premium.product_name', 'Premium');
    }

    /**
     * Whether we are operating against Stripe live mode, inferred from the secret
     * key. Both secret (sk_) and restricted (rk_) keys carry a `_live_` / `_test_`
     * segment, so match on that rather than an `sk_live_` prefix. Keeps test-mode
     * and live-mode price records apart.
     */
    protected function livemode(): bool
    {
        return str_contains((string) config('cashier.secret'), '_live_');
    }

    // --- Stripe SDK seam (overridden in tests so managed-price logic runs without HTTP) ---

    protected function createStripeProduct(string $name): string
    {
        return Cashier::stripe()->products->create(['name' => $name])->id;
    }

    protected function createStripePrice(string $productId, int $amount, string $currency, string $interval): string
    {
        return Cashier::stripe()->prices->create([
            'product' => $productId,
            'unit_amount' => $amount,
            'currency' => $currency,
            'recurring' => ['interval' => $interval],
        ])->id;
    }

    protected function archiveStripePrice(string $priceId): void
    {
        // Stripe Prices are immutable; a superseded price is deactivated, not edited.
        Cashier::stripe()->prices->update($priceId, ['active' => false]);
    }
}
