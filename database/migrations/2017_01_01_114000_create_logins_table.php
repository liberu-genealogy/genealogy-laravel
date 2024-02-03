<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginsTable extends Migration
{
    public function up()
    {
        Schema::create('logins', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->index();
//            $table->foreign('user_id')->references('id')->on('users');

            $table->string('ip');
            $table->text('user_agent');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logins');
    }
}
