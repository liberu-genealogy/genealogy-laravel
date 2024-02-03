<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('menu_id')->unsigned()->nullable();
            $table->foreign('menu_id')->references('id')
                ->on('menus')->onDelete('set null');

            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
