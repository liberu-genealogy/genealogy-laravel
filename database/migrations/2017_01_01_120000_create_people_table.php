<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');

            $table->tinyInteger('title')->nullable();
            $table->string('name')->index();
            $table->string('appellative')->index()->nullable();

            $table->string('uid')->nullable()->unique();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();

            $table->string('bank')->nullable();
            $table->string('bank_account')->nullable();

            $table->text('obs')->nullable();

            $table->integer('created_by')->unsigned()->index()->nullable();
//            $table->foreign('created_by')->references('id')->on('users');

            $table->integer('updated_by')->unsigned()->index()->nullable();
//            $table->foreign('updated_by')->references('id')->on('users');

            $table->timestamps();
        });

//        Schema::table('users', fn (Blueprint $table) => $table->foreign('person_id')->references('id')->on('people')
//        );
    }

    public function down()
    {
        Schema::table('company_person', fn (Blueprint $table) => $table->dropUnique(['person_id', 'company_id'])
        );
        Schema::table('users', fn (Blueprint $table) => $table->dropForeign(['person_id'])
        );

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('people');
        Schema::enableForeignKeyConstraints();
    }
}
