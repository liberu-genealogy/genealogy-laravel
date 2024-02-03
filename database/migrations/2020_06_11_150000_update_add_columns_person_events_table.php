<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddColumnsPersonEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('person_events', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->nullable();
            $table->string('title')->nullable();
            $table->string('date')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('places_id')->nullable()->after('description');
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
        Schema::table('person_events', function (Blueprint $table) {
            $table->dropColumn('person_id');
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('places_id');
            $table->dropColumn('deleted_at');
        });
    }
}
