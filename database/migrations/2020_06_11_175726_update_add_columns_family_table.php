<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddColumnsFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('families', function (Blueprint $table) {
            $table->string('chan')->nullable();
            $table->string('nchi')->nullable();
            $table->string('rin')->nullable();
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
        Schema::table('families', function (Blueprint $table) {
            $table->dropColumn('chan');
            $table->dropColumn('nchi');
            $table->dropColumn('rin');
        });
    }
}
