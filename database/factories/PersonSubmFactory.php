<?php

namespace Database\Factories;

use App\Models\PersonSubm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonSubm>
 */
class PersonSubmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonSubm::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => fake()->word(),
            'gid'   => fake()->randomDigit(),
            'subm'  => fake()->word(),
        ];
    }
}
