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
        Schema::create('face_encodings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->foreignId('source_photo_id')->nullable()->constrained('person_photos')->onDelete('set null');
            $table->text('encoding'); // Store face encoding/features for matching
            $table->string('provider')->default('aws-rekognition'); // Provider used for encoding
            $table->timestamps();
            
            $table->index(['person_id', 'team_id']);
            $table->index('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_encodings');
    }
};
