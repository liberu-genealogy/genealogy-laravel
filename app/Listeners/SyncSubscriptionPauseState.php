<?php

declare(strict_types=1);

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Subscription;

/**
 * Keeps the local paused_at marker in step with Stripe's pause_collection.
 *
 * Stripe leaves a paused subscription's status as "active", so Cashier's own
 * sync does not record the pause. isPremium() revokes access while paused
 * (ADR 0002), and this is how it learns the state — from every
 * customer.subscription.updated, whether the pause was triggered by the app's
 * button or in the Stripe dashboard, so Stripe stays the source of truth.
 */
class SyncSubscriptionPauseState
{
    public function handle(WebhookReceived $event): void
    {
        if (($event->payload['type'] ?? null) !== 'customer.subscription.updated') {
            return;
        }

        $object = $event->payload['data']['object'] ?? [];
        $stripeId = $object['id'] ?? null;

        if ($stripeId === null) {
            return;
        }

        $subscription = Subscription::where('stripe_id', $stripeId)->first();

        if ($subscription === null) {
            return;
        }

        $isPaused = ($object['pause_collection'] ?? null) !== null;
        $subscription->update(['paused_at' => $isPaused ? now() : null]);
    }
}
