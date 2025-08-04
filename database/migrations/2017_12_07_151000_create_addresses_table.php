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
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('region_id')->constrained('regions');
            $table->foreignId('locality_id')->constrained('localities');
            $table->string('city')->nullable();
            $table->string('street');
            $table->string('additional')->nullable();
            $table->string('postcode')->nullable();
            $table->text('notes')->nullable();
            $table->float('lat', 10)->nullable();
            $table->float('long', 10)->nullable();
            $table->boolean('is_default');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
