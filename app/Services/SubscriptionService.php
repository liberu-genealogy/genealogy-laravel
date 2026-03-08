<?php

namespace App\Services;

use App\Models\User;
use Laravel\Cashier\Subscription;

class SubscriptionService
{
    public const PREMIUM_PRICE_ID = 'price_premium_monthly'; // Set this in Stripe
    public const PREMIUM_PRODUCT_ID = 'prod_premium'; // Set this in Stripe

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

        // Real subscription flow using Stripe via Cashier
        $subscriptionBuilder = $user->newSubscription('premium', self::PREMIUM_PRICE_ID)
            ->trialDays(config('subscription.premium.trial_days', 14));

        $subscription = $subscriptionBuilder->create($paymentMethod);

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
     * Get subscription pricing information
     */
    public function getPricingInfo(): array
    {
        $trialDays = config('subscription.premium.trial_days', 14);

        return [
            'premium' => [
                'name' => 'Premium',
                'price' => '$2.99',
                'interval' => 'month',
                'trial_days' => $trialDays,
                'features' => [
                    'Premium user badge',
                    'Unlimited DNA kit uploads',
                    'Duplicate person checker',
                    'Smart matching with public trees',
                    'Priority support',
                    'Advanced charts and reports',
                ],
                'stripe_price_id' => self::PREMIUM_PRICE_ID,
            ]
        ];
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
