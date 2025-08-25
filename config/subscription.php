<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | Stripe API keys and webhook configuration for subscription management
    |
    */
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Premium Subscription Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the premium subscription plan
    |
    */
    'premium' => [
        'price_id' => env('STRIPE_PREMIUM_PRICE_ID', 'price_premium_monthly'),
        'product_id' => env('STRIPE_PREMIUM_PRODUCT_ID', 'prod_premium'),
        'trial_days' => 7,
        'price' => [
            'amount' => 499, // £4.99 in pence
            'currency' => 'gbp',
            'display' => '£4.99',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Limits
    |--------------------------------------------------------------------------
    |
    | Define limits for standard vs premium users
    |
    */
    'limits' => [
        'standard' => [
            'dna_uploads' => 1,
            'duplicate_checks' => 0,
            'smart_matches' => 0,
            'advanced_charts' => false,
            'priority_support' => false,
        ],
        'premium' => [
            'dna_uploads' => -1, // -1 means unlimited
            'duplicate_checks' => -1,
            'smart_matches' => -1,
            'advanced_charts' => true,
            'priority_support' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Premium Features
    |--------------------------------------------------------------------------
    |
    | List of features available to premium users
    |
    */
    'features' => [
        'premium_badge' => [
            'name' => 'Premium Badge',
            'description' => 'Show your premium status with a special badge',
            'premium_only' => true,
        ],
        'unlimited_dna' => [
            'name' => 'Unlimited DNA Uploads',
            'description' => 'Upload unlimited DNA kits for analysis',
            'premium_only' => true,
        ],
        'duplicate_checker' => [
            'name' => 'Duplicate Person Checker',
            'description' => 'Automatically find potential duplicate people in your tree',
            'premium_only' => true,
        ],
        'smart_matching' => [
            'name' => 'Smart Matching',
            'description' => 'Find potential matches in public genealogy databases',
            'premium_only' => true,
        ],
        'priority_support' => [
            'name' => 'Priority Support',
            'description' => 'Get faster response times for support requests',
            'premium_only' => true,
        ],
        'advanced_charts' => [
            'name' => 'Advanced Charts & Reports',
            'description' => 'Access to enhanced visualization tools and detailed reports',
            'premium_only' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Events
    |--------------------------------------------------------------------------
    |
    | Stripe webhook events to handle for subscription management
    |
    */
    'webhook_events' => [
        'customer.subscription.created',
        'customer.subscription.updated',
        'customer.subscription.deleted',
        'customer.subscription.trial_will_end',
        'invoice.payment_succeeded',
        'invoice.payment_failed',
    ],
];