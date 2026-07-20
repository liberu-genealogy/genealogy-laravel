<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fail closed on the Stripe webhook.
 *
 * Cashier's controller verifies the Stripe signature only when
 * cashier.webhook.secret is set — with no secret it attaches no middleware and
 * accepts every request, so a deploy that forgets STRIPE_WEBHOOK_SECRET would
 * honour forged, unsigned events that change subscription state. This rejects
 * the request outright when no secret is configured, so a missing secret is a
 * hard failure rather than an open door. When a secret IS configured, Cashier's
 * own VerifyWebhookSignature does the actual signature check.
 */
class EnsureStripeWebhookIsVerifiable
{
    public function handle(Request $request, Closure $next): Response
    {
        if (blank(config('cashier.webhook.secret'))) {
            abort(403, 'Stripe webhook signature verification is not configured.');
        }

        return $next($request);
    }
}
