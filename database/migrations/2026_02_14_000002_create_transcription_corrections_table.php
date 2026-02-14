<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transcription_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_transcription_id')->constrained('document_transcriptions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('original_text'); // The text before correction
            $table->text('corrected_text'); // The text after correction
            $table->integer('position_start')->nullable(); // Position in document
            $table->integer('position_end')->nullable(); // Position in document
            $table->json('correction_metadata')->nullable(); // Additional context for learning
            $table->timestamps();
            
            $table->index('document_transcription_id');
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transcription_corrections');
    }
};
