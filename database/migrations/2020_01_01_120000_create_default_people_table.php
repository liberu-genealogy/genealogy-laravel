<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultPeopleTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('people')) {
            Schema::create('people', function (Blueprint $table) {
                $table->increments('id');

                $table->tinyInteger('title')->nullable();
                $table->string('name')->index();
                $table->string('appellative')->index()->nullable();

                $table->string('uid')->nullable()->unique();
                $table->string('email')->unique()->nullable();
                $table->string('phone')->nullable();
                $table->string('birthday')->nullable();

                $table->string('bank')->nullable();
                $table->string('bank_account')->nullable();

                $table->text('obs')->nullable();

                $table->integer('created_by')->unsigned()->index()->nullable();

                $table->integer('updated_by')->unsigned()->index()->nullable();

                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('people');
        Schema::enableForeignKeyConstraints();
    }
}
