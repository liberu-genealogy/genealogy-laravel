<?php

return [
    'premium' => [
        // Display-only price string, e.g., "$2.99"
        'price' => env('SUBSCRIPTION_PREMIUM_PRICE', '$2.99'),

        // Billing interval label, e.g., "month" or "year"
        'interval' => env('SUBSCRIPTION_PREMIUM_INTERVAL', 'month'),

        // Trial days for local trial (no payment method)
        'trial_days' => (int) env('SUBSCRIPTION_PREMIUM_TRIAL_DAYS', 14),

        // Stripe price id for real subscriptions created via Cashier
        'stripe_price_id' => env(
        'SUBSCRIPTION_PREMIUM_STRIPE_PRICE_ID',
        // the `.env.example` file historically used STRIPE_PREMIUM_PRICE_ID; keep
        // the old name around for backwards compatibility so deployments that
        // haven't updated yet still work.
        env('STRIPE_PREMIUM_PRICE_ID', env('CASHIER_STRIPE_SUBSCRIPTION_DEFAULT_PRICE_ID'))
    ),
    ],
];
