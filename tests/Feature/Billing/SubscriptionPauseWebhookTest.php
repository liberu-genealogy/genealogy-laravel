<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Subscription;
use Tests\TestCase;

/**
 * Stripe is the source of truth for pause state, so a pause (or resume) done
 * anywhere — the app's own button or the Stripe dashboard — arrives as
 * customer.subscription.updated and must be reflected in the local paused_at
 * marker that isPremium() reads (ADR 0002).
 *
 * Driven by dispatching WebhookReceived rather than posting to the endpoint:
 * Cashier's own customer.subscription.updated handler expects a full Stripe
 * subscription object (items, price, …), which is not what is under test here.
 * The signed-endpoint path itself is covered by StripeWebhookSignatureTest; this
 * isolates the pause-sync listener.
 */
final class SubscriptionPauseWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function subscribe(User $user): Subscription
    {
        return $user->subscriptions()->create([
            'type' => 'premium',
            'stripe_id' => 'sub_x',
            'stripe_status' => 'active',
            'stripe_price' => 'price_premium_monthly',
            'quantity' => 1,
        ]);
    }

    public function test_a_pause_collection_update_marks_the_subscription_paused(): void
    {
        $subscription = $this->subscribe(User::factory()->create());

        event(new WebhookReceived([
            'type' => 'customer.subscription.updated',
            'data' => ['object' => ['id' => 'sub_x', 'pause_collection' => ['behavior' => 'void']]],
        ]));

        $this->assertNotNull($subscription->fresh()->paused_at);
    }

    public function test_clearing_pause_collection_unpauses_the_subscription(): void
    {
        $subscription = $this->subscribe(User::factory()->create());
        $subscription->update(['paused_at' => now()]);

        event(new WebhookReceived([
            'type' => 'customer.subscription.updated',
            'data' => ['object' => ['id' => 'sub_x', 'pause_collection' => null]],
        ]));

        $this->assertNull($subscription->fresh()->paused_at);
    }

    public function test_an_update_for_an_unknown_subscription_is_ignored(): void
    {
        event(new WebhookReceived([
            'type' => 'customer.subscription.updated',
            'data' => ['object' => ['id' => 'sub_missing', 'pause_collection' => ['behavior' => 'void']]],
        ]));

        $this->assertDatabaseCount('subscriptions', 0);
    }
}
