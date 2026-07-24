<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A managed price: the Stripe Product/Price the app auto-creates for a billing
 * interval so no Stripe Dashboard setup is required (ADR 0003). Global — not
 * team-scoped.
 */
class SubscriptionPrice extends Model
{
    protected $fillable = [
        'interval',
        'livemode',
        'stripe_product_id',
        'stripe_price_id',
        'unit_amount',
        'currency',
    ];

    protected $casts = [
        'livemode' => 'boolean',
        'unit_amount' => 'integer',
    ];
}
