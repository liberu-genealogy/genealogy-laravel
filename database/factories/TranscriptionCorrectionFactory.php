<?php

namespace Database\Factories;

use App\Models\TranscriptionCorrection;
use App\Models\DocumentTranscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranscriptionCorrectionFactory extends Factory
{
    protected $model = TranscriptionCorrection::class;

    public function definition(): array
    {
        $original = fake()->sentence();
        $corrected = fake()->sentence();

        return [
            'document_transcription_id' => DocumentTranscription::factory(),
            'user_id' => User::factory(),
            'original_text' => $original,
            'corrected_text' => $corrected,
            'position_start' => fake()->numberBetween(0, 100),
            'position_end' => fake()->numberBetween(101, 200),
            'correction_metadata' => [
                'timestamp' => now()->toIso8601String(),
                'original_confidence' => fake()->randomFloat(2, 0.5, 1.0),
            ],
        ];
    }
}
