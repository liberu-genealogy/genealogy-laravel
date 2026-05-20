<?php

namespace Database\Factories;

use App\Models\SourceRefEven;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SourceRefEven>
 */
class SourceRefEvenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceRefEven::class;

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
            'even'  => fake()->word(),
            'role'  => fake()->word(),
        ];
    }
}
