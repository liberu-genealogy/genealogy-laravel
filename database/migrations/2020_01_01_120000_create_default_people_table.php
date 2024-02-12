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
                $table->bigIncrements('id');
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
                $table->foreignId('created_by')->constrained('users')->nullable();
                $table->foreignId('updated_by')->constrained('users')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
}
