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

    /**
     * Create a trial subscription for a team.
     *
     * @param Team $team The team for which to create the trial subscription.
     *
     * @return void
     */
    public function createTrialSubscription(Team $team)
    {
        $subscription = $this->stripeClient->subscriptions->create([
            'customer' => $team->stripe_customer_id,
            'items'    => [
                ['price' => env('STRIPE_PRICE_ID')],
            ],
            'trial_period_days' => 14,
        ]);

        $team->subscriptions()->create([
            'stripe_subscription_id' => $subscription->id,
            'trial_ends_at'          => now()->addDays(14),
        ]);
    }

    /**
     * Update an existing subscription.
     *
     * @param string $subscriptionId The ID of the subscription to update.
     * @param string $newPlanId      The ID of the new plan.
     *
     * @return array An array containing the result of the operation.
     */
    public function updateSubscription(string $subscriptionId, string $newPlanId): array
    {
        try {
            $stripeService = new StripeApiService();
            $updateResult = $stripeService->updateStripeSubscription($subscriptionId, $newPlanId);

            if (!$updateResult) {
                return ['success' => false, 'message' => 'Error updating subscription with Stripe.'];
            }
            $dbUpdateResult = $databaseService->updateSubscriptionRecord($subscriptionId, $newPlanId);

            return $dbUpdateResult;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error updating subscription: '.$e->getMessage()];
        }
    }

    /**
     * Cancel an existing subscription.
     *
     * @param string $subscriptionId The ID of the subscription to cancel.
     *
     * @return array An array containing the result of the operation.
     */
    public function cancelSubscription(string $subscriptionId): array
    {
        try {
            $stripeService = new StripeApiService();
            $cancelResult = $stripeService->cancelStripeSubscription($subscriptionId);

            if (!$cancelResult) {
                return ['success' => false, 'message' => 'Error cancelling subscription with Stripe.'];
            }
            $databaseService = new DatabaseUpdateService();
            $dbCancelResult = $databaseService->cancelSubscriptionRecord($subscriptionId);

            return $dbCancelResult;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error cancelling subscription: '.$e->getMessage()];
        }
    }
}
