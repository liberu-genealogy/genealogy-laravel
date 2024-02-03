<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonLdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_lds', function (Blueprint $table) {
            $table->id();
            $table->string('group')->nullable();
            $table->integer('gid')->nullable();
            $table->string('type')->nullable();
            $table->string('stat')->nullable();
            $table->string('date')->nullable();
            $table->string('plac')->nullable();
            $table->string('temp')->nullable();
            $table->string('slac_famc')->nullable();
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
        Schema::dropIfExists('person_lds');
    }
}
