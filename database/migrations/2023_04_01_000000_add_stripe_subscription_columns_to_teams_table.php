<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeSubscriptionColumnsToTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            if (!Schema::hasColumn('teams', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->after('name');
            }
            if (!Schema::hasColumn('teams', 'stripe_status')) {
                $table->string('stripe_status')->nullable()->after('stripe_subscription_id');
            }
            if (!Schema::hasColumn('teams', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('stripe_status');
            }
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['stripe_subscription_id', 'stripe_status', 'trial_ends_at']);
        });
    }
}
