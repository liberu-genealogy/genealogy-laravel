<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserGroupPivotTable extends Migration
{
    public function up()
    {
        Schema::create('role_user_group', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('user_group_id')->unsigned()->index();
            $table->foreign('user_group_id')->references('id')->on('user_groups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['role_id', 'user_group_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_user_group');
    }
}
