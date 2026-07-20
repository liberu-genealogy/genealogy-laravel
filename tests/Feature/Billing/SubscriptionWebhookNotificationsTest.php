<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * When a payment fails or a subscription ends, the affected user is told nothing
 * — a lapsed card silently drops them to the free tier. A listener on Cashier's
 * WebhookReceived turns the two user-facing Stripe events into notifications.
 *
 * Driven through the real signed webhook endpoint (STRIPE_WEBHOOK_SECRET is set
 * in phpunit.xml) rather than dispatching the event directly, so the whole path
 * — signature, Cashier controller, listener — is exercised.
 */
final class SubscriptionWebhookNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'whsec_test_secret';

    public function test_a_failed_payment_notifies_the_customer(): void
    {
        $user = User::factory()->create(['stripe_id' => 'cus_failed']);

        $this->postSignedWebhook([
            'id' => 'evt_1',
            'type' => 'invoice.payment_failed',
            'data' => ['object' => ['customer' => 'cus_failed']],
        ])->assertOk();

        $notification = $user->fresh()->notifications->firstOrFail();
        $this->assertSame('subscription_payment_failed', $notification->data['type']);
        $this->assertSame('filament', $notification->data['format']);
    }

    public function test_a_deleted_subscription_notifies_the_customer(): void
    {
        $user = User::factory()->create(['stripe_id' => 'cus_ended']);

        $this->postSignedWebhook([
            'id' => 'evt_2',
            'type' => 'customer.subscription.deleted',
            'data' => ['object' => ['customer' => 'cus_ended']],
        ])->assertOk();

        $notification = $user->fresh()->notifications->firstOrFail();
        $this->assertSame('subscription_ended', $notification->data['type']);
        $this->assertSame('filament', $notification->data['format']);
    }

    public function test_an_event_for_an_unknown_customer_notifies_no_one(): void
    {
        $this->postSignedWebhook([
            'id' => 'evt_3',
            'type' => 'invoice.payment_failed',
            'data' => ['object' => ['customer' => 'cus_nobody']],
        ])->assertOk();

        $this->assertDatabaseCount('notifications', 0);
    }

    private function postSignedWebhook(array $payload): TestResponse
    {
        $body = json_encode($payload);
        $timestamp = time();
        $signature = hash_hmac('sha256', "{$timestamp}.{$body}", self::SECRET);

        return $this->call(
            'POST',
            '/stripe/webhook',
            [], [], [],
            ['HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}", 'CONTENT_TYPE' => 'application/json'],
            $body,
        );
    }
}
