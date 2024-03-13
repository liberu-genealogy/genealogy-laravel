<?php

namespace App\Services;

use App\Models\Team;

class DatabaseUpdateService
{
    public function updateSubscriptionRecord(string $subscriptionId, string $newPlanId): array
    {
        $team = Team::whereHas('subscriptions', function ($query) use ($subscriptionId) {
            $query->where('stripe_subscription_id', $subscriptionId);
        })->first();

        if (!$team) {
            return ['success' => false, 'message' => 'Team not found.'];
        }

        $team->subscriptions()->updateOrCreate(
            ['stripe_subscription_id' => $subscriptionId],
            ['stripe_plan_id' => $newPlanId]
        );

        return ['success' => true, 'message' => 'Subscription updated successfully.'];
    }

    public function cancelSubscriptionRecord(string $subscriptionId): array
    {
        $team = Team::whereHas('subscriptions', function ($query) use ($subscriptionId) {
            $query->where('stripe_subscription_id', $subscriptionId);
        })->first();

        if (!$team) {
            return ['success' => false, 'message' => 'Team not found.'];
        }

        $team->subscriptions()->where('stripe_subscription_id', $subscriptionId)->delete();

        return ['success' => true, 'message' => 'Subscription cancelled successfully.'];
    }
}
