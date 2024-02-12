<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('parent_id')->constrained('menus')->nullable();
            $table->foreignId('permission_id')->constrained('permissions')->nullable();
            $table->string('name')->unique();
            $table->string('icon');
            $table->integer('order_index');
            $table->boolean('has_children');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
