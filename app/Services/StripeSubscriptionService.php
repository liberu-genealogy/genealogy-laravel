<?php

namespace App\Services;

use App\Models\Team;
use Stripe\StripeClient;

class StripeSubscriptionService
{
    private $stripeClient;

    public function __construct()
    {
        $this->stripeClient = new StripeClient(env('STRIPE_SECRET'));
    }

    public function createTrialSubscription(Team $team)
    {
        $subscription = $this->stripeClient->subscriptions->create([
            'customer' => $team->stripe_customer_id,
            'items' => [
                ['price' => env('STRIPE_PRICE_ID')],
            ],
            'trial_period_days' => 14,
        ]);

        $team->subscriptions()->create([
            'stripe_subscription_id' => $subscription->id,
            'trial_ends_at' => now()->addDays(14),
        ]);
    }
}
