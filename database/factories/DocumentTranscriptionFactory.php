<?php

namespace Database\Factories;

use App\Models\DocumentTranscription;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTranscriptionFactory extends Factory
{
    protected $model = DocumentTranscription::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'original_filename' => fake()->word() . '.jpg',
            'document_path' => 'transcriptions/' . fake()->uuid() . '.jpg',
            'raw_transcription' => fake()->paragraphs(3, true),
            'corrected_transcription' => null,
            'metadata' => [
                'confidence' => fake()->randomFloat(2, 0.5, 1.0),
                'language' => 'en',
                'processing_time' => fake()->randomFloat(2, 0.5, 5.0),
            ],
            'status' => 'completed',
            'processed_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'raw_transcription' => null,
            'processed_at' => null,
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
            'raw_transcription' => null,
            'processed_at' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'raw_transcription' => null,
            'metadata' => [
                'error' => 'Processing failed',
            ],
            'processed_at' => null,
        ]);
    }

    public function corrected(): static
    {
        return $this->state(fn (array $attributes) => [
            'corrected_transcription' => fake()->paragraphs(3, true),
        ]);
    }
}
