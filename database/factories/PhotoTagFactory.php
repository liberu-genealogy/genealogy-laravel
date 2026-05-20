<?php

namespace Database\Factories;

use App\Models\PhotoTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoTagFactory extends Factory
{
    protected $model = PhotoTag::class;

    public function definition(): array
    {
        return [
            'confidence' => fake()->randomFloat(2, 70, 99),
            'bounding_box' => [
                'left' => fake()->randomFloat(2, 0.1, 0.5),
                'top' => fake()->randomFloat(2, 0.1, 0.5),
                'width' => fake()->randomFloat(2, 0.15, 0.35),
                'height' => fake()->randomFloat(2, 0.2, 0.4),
            ],
            'status' => 'pending',
            'confirmed_by' => null,
            'confirmed_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'confirmed_by' => null,
            'confirmed_at' => null,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
