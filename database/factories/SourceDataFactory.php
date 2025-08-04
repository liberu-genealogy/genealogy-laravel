<?php

namespace Database\Factories;

use App\Models\SourceData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SourceData>
 */
class SourceDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceData::class;

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
            'text'  => fake()->text(50),
            'agnc'  => fake()->word(),
        ];
    }
}
