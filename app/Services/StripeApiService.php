<?php

namespace App\Services;

use Stripe\StripeClient;

class StripeApiService
{
    private $stripeClient;

    public function __construct()
    {
        $this->stripeClient = new StripeClient(env('STRIPE_SECRET'));
    }

    public function updateStripeSubscription(string $subscriptionId, string $newPlanId)
    {
        return $this->stripeClient->subscriptions->update($subscriptionId, [
            'items' => [
                ['id' => $subscriptionId, 'price' => $newPlanId],
            ],
        ]);
    }

    public function cancelStripeSubscription(string $subscriptionId)
    {
        return $this->stripeClient->subscriptions->cancel($subscriptionId);
    }
}
