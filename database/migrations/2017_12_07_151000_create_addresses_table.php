<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');

            $table->morphs('addressable');

            $table->integer('country_id')->unsigned()->index();
            $table->foreign('country_id')->references('id')->on('countries');

            $table->integer('region_id')->unsigned()->index()->nullable();
            $table->foreign('region_id')->references('id')->on('regions');

            $table->integer('locality_id')->unsigned()->index()->nullable();
            $table->foreign('locality_id')->references('id')->on('localities');

            $table->string('city')->nullable();
            $table->string('street');
            $table->string('additional')->nullable();
            $table->string('postcode')->nullable();
            $table->text('notes')->nullable();

            $table->float('lat', 10, 6)->nullable();
            $table->float('long', 10, 6)->nullable();

            $table->boolean('is_default');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
