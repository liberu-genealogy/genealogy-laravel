<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subns', function (Blueprint $table) {
            $table->id();
            $table->string('subm')->nullable();
            $table->string('famf')->nullable();
            $table->string('temp')->nullable();
            $table->string('ance')->nullable();
            $table->string('desc')->nullable();
            $table->string('ordi')->nullable();
            $table->string('rin')->nullable();

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
        Schema::dropIfExists('subns');
    }
}
