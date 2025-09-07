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
        Schema::create('user_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // e.g., 'person_added', 'family_created', 'achievement_unlocked'
            $table->integer('points');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data about the activity
            $table->foreignId('related_model_id')->nullable(); // ID of related model (person, family, etc.)
            $table->string('related_model_type')->nullable(); // Model class name
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['activity_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_points');
    }
};