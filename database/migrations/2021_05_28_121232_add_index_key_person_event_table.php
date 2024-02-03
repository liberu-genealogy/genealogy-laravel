<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexKeyPersonEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_events', function (Blueprint $table) {
            $table->index(['id', 'person_id', 'addr_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('person_events', function (Blueprint $table) {
            $table->dropIndex(['id', 'person_id', 'addr_id']);
        });
    }
}
