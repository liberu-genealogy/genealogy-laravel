<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_suggested_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('local_person_id')->nullable()->index();
            $table->string('provider')->index();
            $table->string('external_record_id')->index();
            $table->json('candidate_data')->nullable();
            $table->decimal('confidence', 5, 4)->default(0); // 0.0000 - 1.0000
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending')->index();
            $table->timestamps();

            $table->unique(['provider', 'external_record_id', 'local_person_id'], 'ai_suggested_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_suggested_matches');
    }
};
