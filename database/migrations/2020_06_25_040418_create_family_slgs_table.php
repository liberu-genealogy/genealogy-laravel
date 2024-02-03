<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilySlgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_slgs', function (Blueprint $table) {
            $table->id();
            $table->integer('family_id')->nullable();
            $table->string('stat')->nullable();
            $table->string('date')->nullable();
            $table->string('plac')->nullable();
            $table->string('temp')->nullable();
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
        Schema::dropIfExists('family_slgs');
    }
}
