<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records the Stripe Product/Price the application creates and owns for each
 * billing interval (managed prices — see ADR 0003). Global platform pricing,
 * so no team_id: every subscriber pays the same amount for a given interval.
 * Keyed by (interval, livemode) so test-mode and live-mode ids never collide.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_prices', function (Blueprint $table) {
            $table->id();
            $table->string('interval'); // 'month' | 'year'
            $table->boolean('livemode');
            $table->string('stripe_product_id');
            $table->string('stripe_price_id');
            $table->integer('unit_amount'); // minor units (cents)
            $table->string('currency', 3);
            $table->timestamps();

            $table->unique(['interval', 'livemode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_prices');
    }
};
