<?php

namespace App\Services;

use App\Models\User;
use Laravel\Cashier\Subscription;

class SubscriptionService
{
    // Price/product identifiers are stored in configuration/environment so they can
    // be adjusted without changing code. Constants remain as fallbacks primarily for
    // legacy usage.
    public const PREMIUM_PRICE_ID = 'price_premium_monthly'; // default, override via env
    public const PREMIUM_PRODUCT_ID = 'prod_premium'; // default, override via env

    /**
     * Create premium subscription with trial
     *
     * If a payment method is not provided, enable a local 14-day trial without
     * contacting Stripe. This sets the user's generic trial and premium flag
     * so premium checks work immediately. When a payment method is provided,
     * defer to Cashier to create a real Stripe subscription.
     */
    public function createPremiumSubscription(User $user, string $paymentMethod = null)
    {
        // Trial-only flow without requiring a payment method / Stripe setup
        if (empty($paymentMethod)) {
            $trialDays = config('subscription.premium.trial_days', 14);
            $user->forceFill([
                'is_premium' => true,
                'premium_started_at' => now(),
                // Generic trial used by Cashier's Billable::onTrial()
                'trial_ends_at' => now()->addDays($trialDays),
            ])->save();

            return null;
        }

        $priceId = config('subscription.premium.stripe_price_id', self::PREMIUM_PRICE_ID);
        $trialDays = config('subscription.premium.trial_days', 14);

        $subscriptionBuilder = $user->newSubscription('premium', $priceId)
            ->trialDays($trialDays);

        $subscription = $subscriptionBuilder->create($paymentMethod);

        // mark the user locally as premium; Cashier will also update stripe_id, etc.
        $user->update([
            'is_premium' => true,
            'premium_started_at' => now(),
        ]);

        return $subscription;
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
        if ($subscription && ! $subscription->cancelled()) {
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

        if ($subscription && $subscription->cancelled()) {
            $subscription->resume();

            $user->update([
                'is_premium' => true,
            ]);
        }
    }

    /**
     * Get subscription pricing information (display-only).
     */
    public function getPricingInfo(): array
    {
        $trialDays = config('subscription.premium.trial_days', 14);

        return [
            'premium' => [
                'name' => 'Premium',
                'price' => config('subscription.premium.price', '$2.99'),
                'interval' => config('subscription.premium.interval', 'month'),
                'trial_days' => $trialDays,
                'features' => [
                    'Premium user badge',
                    'Unlimited DNA kit uploads',
                    'Duplicate person checker',
                    'Smart matching with public trees',
                    'Priority support',
                    'Advanced charts and reports',
                ],
                'stripe_price_id' => config('subscription.premium.stripe_price_id', self::PREMIUM_PRICE_ID),
            ]
        ];
    }

    /**
     * Build a Stripe Checkout session redirect response.
     *
     * This helper is used by the Filament page to send the user straight to
     * Stripe's hosted checkout form. The returned redirect response may be
     * inspected or sent directly to the browser.
     */
    public function createCheckoutRedirect($user)
    {
        $priceId = config('subscription.premium.stripe_price_id', self::PREMIUM_PRICE_ID);
        $trialDays = config('subscription.premium.trial_days', 7);

        return $user
            ->newSubscription('premium', $priceId)
            ->trialDays($trialDays)
            ->checkout([
                'success_url' => route('filament.app.pages.premium-dashboard'),
                'cancel_url' => route('filament.app.pages.subscription'),
            ]);
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
            ]
        ];
    }
}
