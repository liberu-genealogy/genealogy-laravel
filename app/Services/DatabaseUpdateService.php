<?php

namespace App\Services;

use Laravel\Cashier\Subscription;

class DatabaseUpdateService
{
    public function updateSubscriptionRecord(string $subscriptionId, string $newPlanId): array
    {
        $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

        if (!$subscription) {
            return ['success' => false, 'message' => 'Subscription not found.'];
        }

        $subscription->update(['stripe_price' => $newPlanId]);

        return ['success' => true, 'message' => 'Subscription updated successfully.'];
    }

    public function cancelSubscriptionRecord(string $subscriptionId): array
    {
        $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

        if (!$subscription) {
            return ['success' => false, 'message' => 'Subscription not found.'];
        }

        $subscription->delete();

        return ['success' => true, 'message' => 'Subscription cancelled successfully.'];
    }
}
