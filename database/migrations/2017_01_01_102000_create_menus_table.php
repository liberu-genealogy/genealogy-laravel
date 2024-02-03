<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('menus');

            $table->integer('permission_id')->unsigned()->index()->nullable();
            $table->foreign('permission_id')->references('id')->on('permissions');

            $table->string('name');
            $table->string('icon');
            $table->integer('order_index');

            $table->boolean('has_children');

            $table->timestamps();

            $table->unique(['parent_id', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
