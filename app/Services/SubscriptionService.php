<?php

namespace App\Services;

use App\Models\Team;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionService
{
    public function createTrialSubscription(Team $team)
    {
        $team->newSubscription('default', config('cashier.plans.default.price_id'))
            ->trialDays(config('cashier.plans.default.trial_days'))
            ->create();
    }

    public function getSubscriptionStatus(Team $team): string
    {
        if ($team->subscribed('default')) {
            return 'Active';
        } elseif ($team->onTrial('default')) {
            return 'Trial';
        } else {
            return 'Inactive';
        }
    }

    public function cancelSubscription(Team $team)
    {
        if ($team->subscribed('default')) {
            $team->subscription('default')->cancel();
        }
    }

    public function resumeSubscription(Team $team)
    {
        if ($team->subscription('default')->cancelled()) {
            $team->subscription('default')->resume();
        }
    }

    public function updatePaymentMethod(Team $team, string $paymentMethodId)
    {
        $team->updateDefaultPaymentMethod($paymentMethodId);
    }
}