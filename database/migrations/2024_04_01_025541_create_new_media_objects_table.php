<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('new_media_objects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->integer('gid')->nullable();
            $table->string('group')->nullable();
            $table->string('titl')->nullable();
            $table->string('rin')->nullable(); 
            $table->string('obje_id')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_media_objects');
    }
};
