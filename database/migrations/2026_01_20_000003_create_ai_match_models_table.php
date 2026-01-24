<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_match_models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('default');
            $table->json('weights')->nullable(); // JSON of field weights and meta
            $table->timestamps();
        });

        // Seed a default model
        \Illuminate\Support\Facades\DB::table('ai_match_models')->insert([
            'name' => 'default',
            'weights' => json_encode([
                'first_name' => 1.0,
                'last_name' => 1.0,
                'birth_year' => 0.8,
                'birth_place' => 0.6,
                'parents' => 0.9,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_match_models');
    }
};
