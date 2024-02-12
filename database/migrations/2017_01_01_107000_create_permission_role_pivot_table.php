<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRolePivotTable extends Migration
{
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('permission_id')->constrained('permissions');
            $table->foreignId('role_id')->constrained('roles');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
