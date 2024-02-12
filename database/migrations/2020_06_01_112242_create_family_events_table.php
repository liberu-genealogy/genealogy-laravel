<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('family_id')->references('id')->on('families');
            $table->integer('places_id')->references('id')->on('places')->nullable();
            $table->text('date')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('converted_date')->nullable();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->string('type')->nullable();
            $table->string('plac')->nullable();
            $table->integer('addr_id')->nullable();
            $table->string('phon')->nullable();
            $table->text('caus')->nullable();
            $table->string('age')->nullable();
            $table->string('agnc')->nullable();
            $table->integer('husb')->nullable();
            $table->integer('wife')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('family_events');
    }
}
