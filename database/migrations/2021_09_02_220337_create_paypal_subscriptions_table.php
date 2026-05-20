<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('paypal_id')->unique()->nullable();  // unknown table
            $table->string('user_email')->nullable();
            $table->foreignId('paypal_plan_id')->constrained('paypal_plans')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('start_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypal_subscriptions');
    }
}
