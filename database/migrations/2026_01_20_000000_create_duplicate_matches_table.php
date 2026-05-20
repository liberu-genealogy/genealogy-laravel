<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duplicate_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('primary_person_id')->constrained('people')->cascadeOnDelete();
            $table->foreignId('duplicate_person_id')->constrained('people')->cascadeOnDelete();
            $table->decimal('confidence_score', 5, 4)->default(0.0);
            $table->json('match_data')->nullable();
            $table->string('status')->default('pending'); // pending | reviewed | accepted | rejected | merged
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['primary_person_id', 'duplicate_person_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duplicate_matches');
    }
};
