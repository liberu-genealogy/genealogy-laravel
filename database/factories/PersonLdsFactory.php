<?php

namespace Database\Factories;

use App\Models\PersonLds;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonLds>
 */
class PersonLdsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonLds::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group'     => fake()->word(),
            'gid'       => fake()->randomDigit(),
            'type'      => fake()->word(),
            'stat'      => fake()->word(),
            'date'      => fake()->date(),
            'plac'      => fake()->word(),
            'temp'      => fake()->text(50),
            'slac_famc' => fake()->word(),
        ];
    }
}
