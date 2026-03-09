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
        Schema::create('person_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_analyzed')->default(false);
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();
            
            $table->index(['person_id', 'team_id']);
            $table->index('is_analyzed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_photos');
    }
};
