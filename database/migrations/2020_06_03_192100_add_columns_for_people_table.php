<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('gid')->nullable();
            $table->string('givn')->nullable();
            $table->string('surn', 191)->nullable();
            $table->string('name', 191)->nullable()->change();

            $table->string('type')->nullable();
            $table->string('npfx')->nullable();
            $table->string('nick')->nullable();
            $table->string('spfx')->nullable();
            $table->string('nsfx')->nullable();

            $table->char('sex', 1)->nullable();
            $table->text('description')->nullable();
            $table->integer('child_in_family_id')->references('id')->on('families')->nullable();
            $table->softDeletes();
            //    $table->dropColumn('bank');
            //    $table->dropColumn('bank_account');
        //    $table->dropUnique(['uid']);
        });
    }

    public function down()
    {
        Schema::table('people', function ($table) {
            //    $table->string('bank');
            //    $table->string('bank_account');
            $table->dropColumn('gid');
            $table->dropColumn('givn');
            $table->dropColumn('surn');
            $table->string('name')->nullable(false)->change();
            $table->dropColumn('sex');
            $table->dropColumn('description');
            $table->dropColumn('child_in_family_id');
            $table->dropColumn('deleted_at');
        });
    }
}
