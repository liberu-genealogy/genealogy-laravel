<?php

namespace App\Services;

use App\Models\Team;

/**
 * Updates the subscription record in the database with a new plan ID.
 *
 * @param string $subscriptionId The ID of the subscription to update.
 * @param string $newPlanId The ID of the new plan.
 * @return array An array containing a success status and a message.
 */
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
/**
 * Service for updating subscription records in the database.
 * This includes operations such as updating subscription plans and cancelling subscriptions.
 */

        $team->subscriptions()->where('stripe_subscription_id', $subscriptionId)->delete();

        return ['success' => true, 'message' => 'Subscription cancelled successfully.'];
    }
}
