<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\FamilySlgs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FamilySlgs>
 */
class FamilySlgsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FamilySlgs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'family_id' => Family::create()->id,
            'stat'      => fake()->word(),
            'date'      => fake()->date,
            'plac'      => fake()->word(),
            'temp'      => fake()->word(),
        ];
    }
}
