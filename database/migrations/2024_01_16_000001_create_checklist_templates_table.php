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
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('general');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_default')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->json('tags')->nullable();
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->integer('estimated_time')->nullable()->comment('Estimated time in minutes');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'is_public']);
            $table->index(['is_default', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_templates');
    }
};