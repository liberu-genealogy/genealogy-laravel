<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smart_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained()->cascadeOnDelete();
            $table->string('external_tree_id')->nullable();
            $table->string('external_person_id')->nullable();
            $table->string('match_source'); // 'familysearch', 'ancestry', 'myheritage', etc.
            $table->json('match_data');
            $table->decimal('confidence_score', 5, 2)->default(0.00);
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['person_id', 'confidence_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smart_matches');
    }
};