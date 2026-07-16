<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\FamilySlgs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FamilySlgs>
 */
class FamilySlgsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = FamilySlgs::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'family_id' => Family::create()->id,
            'stat' => fake()->word(),
            'date' => fake()->date,
            'plac' => fake()->word(),
            'temp' => fake()->word(),
        ];
    }
}
