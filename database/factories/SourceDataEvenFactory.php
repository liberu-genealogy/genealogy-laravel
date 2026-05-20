<?php

namespace Database\Factories;

use App\Models\SourceDataEven;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SourceDataEven>
 */
class SourceDataEvenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceDataEven::class;

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
            'date'  => fake()->date(),
            'plac'  => fake()->word(),
        ];
    }
}
