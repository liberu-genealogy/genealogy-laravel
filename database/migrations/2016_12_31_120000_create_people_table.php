<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('title')->nullable(); // needs type change
            $table->string('name', 191)->index()->nullable();
            $table->string('appellative')->index()->nullable();
            $table->string('uid')->index()->nullable()->unique();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            // $table->unsignedTinyInteger('birth_month')->nullable();
            // $table->smallInteger('birth_year')->nullable(); // duplicate ????
            // $table->string('deathday')->nullable();
            // $table->unsignedTinyInteger('death_month')->nullable();
            // $table->smallInteger('death_year'); // duplicate??
            // $table->string('burial_day')->nullable();  // duplicate??
            // $table->unsignedTinyInteger('burial_month')->nullable(); // duplicate??
            // $table->smallInteger('burial_year')->nullable(); // duplicate??
            $table->string('bank')->nullable();
            $table->string('bank_account')->nullable();
            $table->text('obs')->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('updated_by')->unsigned()->index()->nullable();
            // $table->string('gid')->nullable();
            // $table->string('givn')->nullable();
            // $table->string('surn', 191)->nullable();
            // $table->string('type')->nullable();
            // $table->string('npfx')->nullable();
            // $table->string('nick')->nullable();
            // $table->string('spfx')->nullable();
            // $table->string('nsfx')->nullable();
            // $table->string('titl')->nullable();
            // $table->string('chr')->nullable();
            // $table->string('rin')->nullable();
            // $table->string('resn')->nullable();
            // $table->string('rfn')->nullable();
            // $table->string('afn')->nullable();
            // $table->string('chan')->nullable();
            // $table->char('sex', 1)->nullable();
            // $table->text('description')->nullable();
            // $table->string('child_in_family_id')->nullable(); // unknown table
            // $table->string('birthday_dati')->nullable(); // duplicate??
            // $table->string('birthday_plac')->nullable(); // duplicate??
            // $table->string('deathday_dati')->nullable(); // duplicate??
            // $table->string('deathday_plac')->nullable(); // duplicate??
            // $table->string('deathday_caus')->nullable(); // duplicate??
            // $table->string('burial_day_dati')->nullable(); // duplicate??
            // $table->string('burial_day_plac')->nullable(); // duplicate??
            // $table->string('famc')->nullable(); // duplicate??
            // $table->string('fams')->nullable(); // duplicate??

            // $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
}
