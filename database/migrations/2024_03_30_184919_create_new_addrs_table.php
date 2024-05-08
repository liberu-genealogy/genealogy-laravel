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
        Schema::create('new_addrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('adr1')->nullable();
            $table->string('adr2')->nullable();
            $table->string('city')->nullable();
            $table->string('stae')->nullable();
            $table->string('post')->nullable();
            $table->string('ctry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_addrs');
    }
};
