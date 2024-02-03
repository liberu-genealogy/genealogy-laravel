<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDatiPlacFamcFamsToPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('birthday_dati')->nullable();
            $table->string('birthday_plac')->nullable();
            $table->string('deathday_dati')->nullable();
            $table->string('deathday_plac')->nullable();
            $table->string('deathday_caus')->nullable();
            $table->string('burial_day_dati')->nullable();
            $table->string('burial_day_plac')->nullable();
            $table->string('famc')->nullable();
            $table->string('fams')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn([
                'birthday_dati',
                'birthday_plac',
                'deathday_dati',
                'deathday_plac',
                'deathday_caus',
                'burial_day_dati',
                'burial_day_plac',
                'famc',
                'fams',
            ]);
        });
    }
}
