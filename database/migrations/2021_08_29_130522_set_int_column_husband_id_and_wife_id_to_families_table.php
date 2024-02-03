<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetIntColumnHusbandIdAndWifeIdToFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('families', function (Blueprint $table) {
            $table->unsignedInteger('husband_id')->nullable()->change();
            $table->unsignedInteger('wife_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('families', function (Blueprint $table) {
            $table->string('husband_id')->nullable()->change();
            $table->string('wife_id')->nullable()->change();
        });
    }
}
