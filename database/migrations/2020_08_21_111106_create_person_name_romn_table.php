<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonNameRomnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_name_romn', function (Blueprint $table) {
            $table->id();
            $table->string('group')->nullable();
            $table->integer('gid')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('npfx')->nullable();
            $table->string('givn')->nullable();
            $table->string('nick')->nullable();
            $table->string('spfx')->nullable();
            $table->string('surn')->nullable();
            $table->string('nsfx')->nullable();
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
        Schema::dropIfExists('person_name_romn');
    }
}
