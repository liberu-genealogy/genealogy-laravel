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
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->integer('current_progress')->default(0);
            $table->integer('target_progress');
            $table->json('progress_data')->nullable(); // Store detailed progress information
            $table->timestamp('started_at');
            $table->timestamp('last_updated_at');
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']);
            $table->index(['user_id', 'last_updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};