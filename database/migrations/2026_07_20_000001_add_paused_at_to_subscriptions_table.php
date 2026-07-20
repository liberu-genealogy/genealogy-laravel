<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records when a subscription is paused (Stripe pause_collection). Stripe leaves
 * a paused subscription's status as "active", so this local marker is how the
 * app knows to revoke premium access while paused (ADR 0002). Kept in sync from
 * the customer.subscription.updated webhook.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table): void {
            if (! Schema::hasColumn('subscriptions', 'paused_at')) {
                $table->timestamp('paused_at')->nullable()->after('ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table): void {
            if (Schema::hasColumn('subscriptions', 'paused_at')) {
                $table->dropColumn('paused_at');
            }
        });
    }
};
