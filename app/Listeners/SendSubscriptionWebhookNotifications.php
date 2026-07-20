<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use App\Notifications\SubscriptionEndedNotification;
use App\Notifications\SubscriptionPaymentFailedNotification;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;

/**
 * Notifies a subscriber about the two Stripe events that affect them directly.
 * Cashier's controller already keeps subscription state in sync; this only adds
 * the user-facing notification. Payment retries and dunning stay with Stripe.
 */
class SendSubscriptionWebhookNotifications
{
    public function handle(WebhookReceived $event): void
    {
        $type = $event->payload['type'] ?? null;

        $notification = match ($type) {
            'invoice.payment_failed' => new SubscriptionPaymentFailedNotification,
            'customer.subscription.deleted' => new SubscriptionEndedNotification,
            default => null,
        };

        if ($notification === null) {
            return;
        }

        $customerId = $event->payload['data']['object']['customer'] ?? null;

        // Cashier types findBillable() as the Billable trait; in this app the only
        // billable model is User, which is what carries Notifiable.
        /** @var User|null $user */
        $user = $customerId ? Cashier::findBillable($customerId) : null;

        $user?->notify($notification);
    }
}
