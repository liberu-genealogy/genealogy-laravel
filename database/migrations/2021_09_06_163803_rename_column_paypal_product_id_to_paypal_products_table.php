<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnPaypalProductIdToPaypalProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paypal_products', function (Blueprint $table) {
            $table->renameColumn('paypal_product_id', 'paypal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paypal_products', function (Blueprint $table) {
            $table->renameColumn('paypal_id', 'paypal_product_id');
        });
    }
}
