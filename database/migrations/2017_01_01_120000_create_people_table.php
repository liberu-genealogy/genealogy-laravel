<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('title')->nullable();
            $table->string('name', 191)->index()->nullable();
            $table->string('appellative')->index()->nullable();
            $table->string('uid')->index()->nullable()->unique();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('birth_month')->nullable();
            $table->smallInteger('birth_year')->nullable();
            $table->string('deathday')->nullable();
            $table->unsignedTinyInteger('death_month')->nullable();
            $table->smallInteger('death_year');
            $table->string('burial_day')->nullable();
            $table->unsignedTinyInteger('burial_month')->nullable();
            $table->smallInteger('burial_year')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_account')->nullable();
            $table->text('obs')->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('updated_by')->unsigned()->index()->nullable();
            $table->string('gid')->nullable();
            $table->string('givn')->nullable();
            $table->string('surn', 191)->nullable();
            $table->string('type')->nullable();
            $table->string('npfx')->nullable();
            $table->string('nick')->nullable();
            $table->string('spfx')->nullable();
            $table->string('nsfx')->nullable();
            $table->string('titl')->nullable();
            $table->string('chr')->nullable();
            $table->string('rin')->nullable();
            $table->string('resn')->nullable();
            $table->string('rfn')->nullable();
            $table->string('afn')->nullable();
            $table->string('chan')->nullable();
            $table->char('sex', 1)->nullable();
            $table->text('description')->nullable();
            $table->integer('child_in_family_id')->references('id')->on('families')->nullable();
            $table->string('birthday_dati')->nullable();
            $table->string('birthday_plac')->nullable();
            $table->string('deathday_dati')->nullable();
            $table->string('deathday_plac')->nullable();
            $table->string('deathday_caus')->nullable();
            $table->string('burial_day_dati')->nullable();
            $table->string('burial_day_plac')->nullable();
            $table->string('famc')->nullable();
            $table->string('fams')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
}
