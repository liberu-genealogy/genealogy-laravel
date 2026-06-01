<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('paypal_plans', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('paypal_id')->unique()->nullable();  // unknown table
            $table->string('paypal_product_id')->constrained('paypal_products')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->string('usage_type')->nullable();
            $table->dateTime('create_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('paypal_plans');
    }
}
