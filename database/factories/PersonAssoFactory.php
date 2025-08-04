<?php

namespace Database\Factories;

use App\Models\PersonAsso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonAsso>
 */
class PersonAssoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonAsso::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group'          => fake()->word(),
            'gid'            => fake()->randomDigit(),
            'indi'           => fake()->word(),
            'rela'           => fake()->word(),
            'import_confirm' => fake()->randomDigit(),
        ];
    }
}
