<?php

declare(strict_types=1);

return [
    'premium' => [
        // Require a card before granting premium access. When true, the no-card
        // local-trial path is unavailable and the only route to premium is a
        // Stripe checkout with a card on file. Default true for this platform.
        'require_card' => (bool) env('SUBSCRIPTION_REQUIRE_CARD', true),

        // Trial length in days. Zero is a first-class value meaning "no trial" —
        // the subscriber is charged immediately at checkout.
        'trial_days' => (int) env('SUBSCRIPTION_PREMIUM_TRIAL_DAYS', 14),

        // Name of the Stripe Product the app auto-creates (managed price, ADR 0003).
        'product_name' => env('SUBSCRIPTION_PREMIUM_PRODUCT_NAME', 'Premium'),

        // Proration behavior when a subscriber switches interval (monthly<->yearly)
        // from the Stripe billing portal. Stripe owns the maths; this only picks
        // the policy. One of: 'create_prorations' | 'none' | 'always_invoice'.
        'portal_proration' => env('SUBSCRIPTION_PREMIUM_PORTAL_PRORATION', 'create_prorations'),

        // Price amounts in minor units (cents), per billing interval. The app
        // creates/owns the Stripe Price from these; the displayed price string is
        // derived from the amount + currency so it can never drift from the charge.
        'amounts' => [
            'month' => (int) env('SUBSCRIPTION_PREMIUM_MONTHLY_AMOUNT', 299),
            'year' => (int) env('SUBSCRIPTION_PREMIUM_YEARLY_AMOUNT', 2999),
        ],
    ],
];
