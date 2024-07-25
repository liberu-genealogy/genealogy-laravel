<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBirthdayIndexToPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->index('birthday');
        });
    }

    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropIndex(['birthday']);
        });
    }
}