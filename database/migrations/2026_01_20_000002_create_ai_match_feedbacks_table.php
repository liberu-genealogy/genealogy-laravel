<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_match_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suggested_match_id')->constrained('ai_suggested_matches')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('action', ['confirm', 'reject']);
            $table->json('payload')->nullable(); // optional details
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_match_feedbacks');
    }
};
