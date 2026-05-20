<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tenant_person_id')->unique(); // unknown table
            $table->foreignId('tenant_id')->constrained('tenants')->unique();
            $table->string('name')->nullable();
            $table->string('appellative', 191)->nullable();
            $table->string('nin', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('phone', 191)->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('birth_month')->nullable();
            $table->smallInteger('birth_year')->nullable();
            $table->string('deathday', 191)->nullable();
            $table->tinyInteger('death_month')->nullable();
            $table->smallInteger('death_year')->nullable();
            $table->string('burial_day', 191)->nullable();
            $table->tinyInteger('burial_month')->nullable();
            $table->smallInteger('burial_year')->nullable();
            $table->string('bank', 191)->nullable();
            $table->string('bank_account', 191)->nullable();
            $table->text('notes')->nullable();
            $table->string('gid', 191)->nullable();
            $table->string('givn', 191)->nullable();
            $table->string('surn')->nullable();
            $table->string('uid', 191)->nullable();
            $table->string('type', 191)->nullable();
            $table->string('npfx', 191)->nullable();
            $table->string('nick', 191)->nullable();
            $table->string('spfx', 191)->nullable();
            $table->string('nsfx', 191)->nullable();
            $table->char('sex', 1)->nullable();
            $table->text('description')->nullable();
            // $table->foreignId('child_in_family_id')->nullable(); // unknown table
            $table->string('chan', 191)->nullable();
            $table->string('rin', 191)->nullable();
            $table->string('resn', 191)->nullable();
            $table->string('rfn', 191)->nullable();
            $table->string('afn', 191)->nullable();
            $table->string('birthday_dati', 191)->nullable();
            $table->string('birthday_plac', 191)->nullable();
            $table->string('deathday_dati', 191)->nullable();
            $table->string('deathday_plac', 191)->nullable();
            $table->string('deathday_caus', 191)->nullable();
            $table->string('burial_day_dati', 191)->nullable();
            $table->string('burial_day_plac', 191)->nullable();
            $table->string('famc', 191)->nullable();
            $table->string('fams', 191)->nullable();
            $table->string('titl', 191)->nullable();
            $table->string('chr', 191)->nullable();

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
        Schema::dropIfExists('tenant_people');
    }
};
