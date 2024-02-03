<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('file_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('model')->unique()->nullable();
            $table->string('folder')->nullable();
            $table->string('icon')->nullable();
            $table->string('endpoint')->nullable();

            $table->text('description')->nullable();

            $table->boolean('is_browsable');
            $table->boolean('is_system');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_types');
    }
};
