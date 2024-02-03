<?php

namespace Database\Factories;

use App\Models\PersonLds;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'group' => $this->faker->word(),
            'gid' => $this->faker->randomDigit('1', '2'),
            'type' => $this->faker->word(),
            'stat' => $this->faker->word(),
            'date' => $this->faker->date(),
            'plac' => $this->faker->word(),
            'temp' => $this->faker->text(50),
            'slac_famc' => $this->faker->word(),
        ];
    }
}
