<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records the Stripe billing-portal Configuration the application creates and
 * owns, so the "Manage Billing" portal offers monthly<->yearly plan switching
 * without any Stripe Dashboard setup (the same managed-object philosophy as the
 * managed prices, ADR 0003). Keyed by livemode so test/live ids never collide.
 * The referenced price ids are stored so the config auto-heals when a managed
 * price changes (a portal Configuration, unlike a Price, is updatable in place).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_portal_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('livemode');
            $table->string('stripe_configuration_id');
            $table->string('stripe_month_price_id');
            $table->string('stripe_year_price_id');
            $table->timestamps();

            $table->unique('livemode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_portal_configs');
    }
};
