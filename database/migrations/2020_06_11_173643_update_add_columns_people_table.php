<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddColumnsPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('people', function (Blueprint $table) {
            $table->string('chan')->nullable();
            $table->string('rin')->nullable();
            $table->string('resn')->nullable();
            $table->string('rfn')->nullable();
            $table->string('afn')->nullable();
            $table->string('deathday')->nullable()->after('birthday');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('chan');
            $table->dropColumn('rin');
            $table->dropColumn('resn');
            $table->dropColumn('rfn');
            $table->dropColumn('afn');
            $table->dropColumn('deathday');
        });
    }
}
