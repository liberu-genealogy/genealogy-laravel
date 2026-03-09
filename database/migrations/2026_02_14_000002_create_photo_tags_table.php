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
        Schema::create('photo_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained('person_photos')->onDelete('cascade');
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->decimal('confidence', 5, 2)->nullable();
            $table->json('bounding_box')->nullable(); // Store face coordinates
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            $table->index(['photo_id', 'status']);
            $table->index(['person_id', 'status']);
            $table->index('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_tags');
    }
};
