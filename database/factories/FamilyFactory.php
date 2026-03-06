<?php

namespace Database\Factories;

use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Family::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => fake()->text(),
            'is_active'   => fake()->boolean(),
            'husband_id'  => null,
            'wife_id'     => null,
            'type_id'     => null,
            'chan'         => fake()->word(),
            'nchi'        => (string) fake()->randomDigit(),
            'rin'         => fake()->word(),
        ];
    }
}
