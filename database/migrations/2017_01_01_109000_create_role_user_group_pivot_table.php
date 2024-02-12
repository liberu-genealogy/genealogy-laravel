<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserGroupPivotTable extends Migration
{
    public function up()
    {
        Schema::create('role_user_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('user_group_id')->constrained('user_groups');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_user_group');
    }
}
