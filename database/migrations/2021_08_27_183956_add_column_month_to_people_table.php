<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMonthToPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->unsignedTinyInteger('birth_month')->nullable()->after('birthday');
            $table->unsignedTinyInteger('death_month')->nullable()->after('deathday');
            $table->unsignedTinyInteger('burial_month')->nullable()->after('burial_day');
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
            $table->dropColumn(['birth_month', 'death_month', 'burial_month']);
        });
    }
}
