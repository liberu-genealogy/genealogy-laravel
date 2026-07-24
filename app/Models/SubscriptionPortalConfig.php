<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The Stripe billing-portal Configuration the app auto-creates so the hosted
 * portal offers monthly<->yearly plan switching without Dashboard setup (see
 * ADR 0003 for the managed-object pattern). Global — not team-scoped. The
 * referenced price ids let the config auto-heal when a managed price changes.
 */
class SubscriptionPortalConfig extends Model
{
    protected $fillable = [
        'livemode',
        'stripe_configuration_id',
        'stripe_month_price_id',
        'stripe_year_price_id',
    ];

    protected $casts = [
        'livemode' => 'boolean',
    ];
}
