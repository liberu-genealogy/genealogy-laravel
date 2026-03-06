<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->string('gid')->nullable();
            $table->string('givn')->nullable()->index();
            $table->string('surn', 191)->nullable()->index();
            $table->char('sex', 1)->nullable();
            $table->integer('child_in_family_id')->nullable();
            $table->text('description')->nullable();
            $table->string('titl')->nullable();
            $table->string('name', 191)->nullable()->index();
            $table->string('appellative')->nullable()->index();
            $table->string('uid')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo_url')->nullable();
            $table->date('birthday')->nullable();
            $table->date('deathday')->nullable();
            $table->date('burial_day')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('chan')->nullable();
            $table->string('rin')->nullable();
            $table->string('resn')->nullable();
            $table->string('rfn')->nullable();
            $table->string('afn')->nullable();
            $table->float('tree_position_x')->nullable();
            $table->float('tree_position_y')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

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
        Schema::dropIfExists('persons');
    }
}
