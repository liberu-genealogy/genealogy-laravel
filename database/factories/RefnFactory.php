<?php

namespace Database\Factories;

use App\Models\Refn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Refn>
 */
class RefnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Refn::class;

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
            'refn'  => fake()->word(),
            'type'  => fake()->word(),
        ];
    }
}
