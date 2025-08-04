<?php

namespace Database\Factories;

use App\Models\Subn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subn>
 */
class SubnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subn::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'subm' => fake()->word(),
            'famf' => fake()->word(),
            'temp' => fake()->word(),
            'ance' => fake()->word(),
            'desc' => fake()->randomDigit(),
            'ordi' => fake()->word(),
            'rin'  => fake()->word(),
        ];
    }
}
