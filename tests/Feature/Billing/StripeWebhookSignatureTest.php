<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The /stripe/webhook endpoint changes subscription state, so only genuinely
 * Stripe-signed requests may reach it.
 *
 * Cashier's controller applies its signature check only when
 * cashier.webhook.secret is set. That is fail-open: a deploy that forgets
 * STRIPE_WEBHOOK_SECRET attaches no middleware, so a forged, unsigned event is
 * accepted and can flip a user's subscription. A guard middleware makes the
 * endpoint fail closed — no configured secret means no webhook is honoured.
 *
 * STRIPE_WEBHOOK_SECRET is set in phpunit.xml so the signature path is live for
 * the signed/unsigned cases; the last test clears it to prove the fail-closed
 * guard.
 */
final class StripeWebhookSignatureTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'whsec_test_secret';

    public function test_an_unsigned_webhook_is_rejected(): void
    {
        $this->postJson('/stripe/webhook', ['type' => 'invoice.payment_succeeded'])
            ->assertForbidden();
    }

    public function test_a_wrongly_signed_webhook_is_rejected(): void
    {
        $payload = json_encode(['type' => 'invoice.payment_succeeded', 'data' => ['object' => []]]);

        $this->postWebhook($payload, 't='.time().',v1=deadbeefdeadbeef')->assertForbidden();
    }

    public function test_a_correctly_signed_webhook_is_accepted(): void
    {
        // An event type Cashier has no handler for -> its missingMethod() path
        // returns 200 without touching the database, isolating this test to the
        // signature check alone.
        $payload = json_encode(['id' => 'evt_test', 'type' => 'charge.succeeded', 'data' => ['object' => []]]);
        $timestamp = time();
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", self::SECRET);

        $this->postWebhook($payload, "t={$timestamp},v1={$signature}")->assertOk();
    }

    public function test_the_endpoint_fails_closed_when_no_secret_is_configured(): void
    {
        config(['cashier.webhook.secret' => null]);

        // A perfectly-formed but unverifiable request must still be rejected,
        // not accepted — a missing secret is a misconfiguration, not permission.
        $payload = json_encode(['id' => 'evt_test', 'type' => 'charge.succeeded', 'data' => ['object' => []]]);

        $this->postWebhook($payload, 't='.time().',v1=whatever')->assertForbidden();
    }

    /**
     * POST a raw body so the bytes match what the signature was computed over —
     * postJson would re-encode and break the signature.
     */
    private function postWebhook(string $rawBody, string $signature): TestResponse
    {
        return $this->call(
            'POST',
            '/stripe/webhook',
            [], [], [],
            ['HTTP_STRIPE_SIGNATURE' => $signature, 'CONTENT_TYPE' => 'application/json'],
            $rawBody,
        );
    }
}
