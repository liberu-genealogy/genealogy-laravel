<?php

declare(strict_types=1);

return [
    'plans' => [
        'default' => [
            'price_id'        => ENV('CASHIER_STRIPE_SUBSCRIPTION_DEFAULT_PRICE_ID'),
            'trial_days'      => 14, // Optional
            'collect_tax_ids' => false, // Optional
        ],
    ],
];
