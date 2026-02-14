<?php

namespace Database\Factories;

use App\Models\PersonPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonPhotoFactory extends Factory
{
    protected $model = PersonPhoto::class;

    public function definition(): array
    {
        return [
            'file_path' => 'person-photos/' . fake()->uuid() . '.jpg',
            'file_name' => fake()->firstName() . '-' . fake()->lastName() . '.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(50000, 5000000),
            'width' => fake()->numberBetween(400, 2000),
            'height' => fake()->numberBetween(400, 2000),
            'description' => fake()->optional()->sentence(),
            'is_analyzed' => fake()->boolean(),
            'analyzed_at' => fake()->optional()->dateTime(),
        ];
    }

    public function unanalyzed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_analyzed' => false,
            'analyzed_at' => null,
        ]);
    }

    public function analyzed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_analyzed' => true,
            'analyzed_at' => now(),
        ]);
    }
}
