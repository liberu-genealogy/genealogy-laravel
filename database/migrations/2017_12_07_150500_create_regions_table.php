<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('country_id')->index();
            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('restrict')->onDelete('restrict');

            $table->string('abbreviation', 2);
            $table->string('name');

            $table->boolean('is_active');

            $table->unique(['country_id', 'abbreviation']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
