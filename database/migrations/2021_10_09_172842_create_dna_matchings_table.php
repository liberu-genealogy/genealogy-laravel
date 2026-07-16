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
        Schema::create('dna_matchings', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('image')->nullable();
            $table->string('file1');
            $table->string('file2');
            $table->string('total_shared_cm')->nullable();
            $table->string('largest_cm_segment')->nullable();
            $table->foreignId('match_id')->nullable(); // unknown table
            $table->string('match_name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dna_matchings');
    }
};
