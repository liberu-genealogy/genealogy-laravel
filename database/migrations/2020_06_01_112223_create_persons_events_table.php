<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_events', function (Blueprint $table) {
            $table->id()->index();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->string('type')->nullable();
            $table->text('attr')->nullable();
            $table->string('plac')->nullable();
            $table->integer('addr_id')->index()->nullable();
            $table->string('phon')->nullable();
            $table->text('caus')->nullable();
            $table->string('age')->nullable();
            $table->string('agnc')->nullable();
            $table->string('adop')->nullable();
            $table->string('adop_famc')->nullable();
            $table->string('birt_famc')->nullable();
            $table->string('converted_date')->nullable();
            $table->unsignedBigInteger('person_id')->index()->nullable();
            $table->string('title')->nullable();
            $table->string('date')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('places_id')->nullable();

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
        Schema::dropIfExists('person_events');
    }
}
