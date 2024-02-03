<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 255);

            $table->string('iso_3166_2', 2);
            $table->string('iso_3166_3', 3);

            $table->string('capital', 255)->nullable();
            $table->string('citizenship', 255)->nullable();
            $table->string('country_code', 3)->nullable();

            $table->string('currency', 255)->nullable();
            $table->string('currency_code', 255)->nullable();
            $table->string('currency_sub_unit', 255)->nullable();
            $table->string('currency_symbol', 3)->nullable();
            $table->integer('currency_decimals')->nullable();

            $table->string('full_name', 255)->nullable();
            $table->string('region_code', 3)->default('');
            $table->string('sub_region_code', 3)->default('');

            $table->boolean('eea')->default(0);
            $table->string('calling_code', 3)->nullable();
            $table->string('flag', 6)->nullable();
            $table->boolean('is_active')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
