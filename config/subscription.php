<?php

return [
    'premium' => [
        // Display-only price string, e.g., "£4.99"
        'price' => env('SUBSCRIPTION_PREMIUM_PRICE', '£4.99'),

        // Billing interval label, e.g., "month" or "year"
        'interval' => env('SUBSCRIPTION_PREMIUM_INTERVAL', 'month'),

        // Trial days for local trial (no payment method)
        'trial_days' => (int) env('SUBSCRIPTION_PREMIUM_TRIAL_DAYS', 7),

        // Stripe price id for real subscriptions created via Cashier
        'stripe_price_id' => env('SUBSCRIPTION_PREMIUM_STRIPE_PRICE_ID', env('CASHIER_STRIPE_SUBSCRIPTION_DEFAULT_PRICE_ID')),
    ],
];
