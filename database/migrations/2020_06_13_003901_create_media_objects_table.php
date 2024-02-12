<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_objects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gid')->nullable();
            $table->string('group')->nullable();
            $table->string('titl')->nullable();
            // $table->string('rin')->nullable(); // duplicate??
            $table->string('obje_id')->nullable(); // don't know what type must be

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
        Schema::dropIfExists('media_objects');
    }
}
