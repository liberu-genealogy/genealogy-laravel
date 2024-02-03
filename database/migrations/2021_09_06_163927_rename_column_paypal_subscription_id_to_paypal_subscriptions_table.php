<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnPaypalSubscriptionIdToPaypalSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paypal_subscriptions', function (Blueprint $table) {
            $table->renameColumn('paypal_subscription_id', 'paypal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paypal_subscriptions', function (Blueprint $table) {
            $table->renameColumn('paypal_id', 'paypal_subscription_id');
        });
    }
}
