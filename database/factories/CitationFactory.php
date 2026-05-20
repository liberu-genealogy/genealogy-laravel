<?php

namespace Database\Factories;

use App\Models\Citation;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Citation>
 */
class CitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Citation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => fake()->word,
            'date'        => fake()->date,
            'description' => fake()->text(50),
            // 'repository_id' => Repository::create()->id,
            'volume'     => fake()->randomDigit(),
            'page'       => fake()->randomDigit(),
            'is_active'  => fake()->randomDigit(),
            'confidence' => fake()->randomDigit(),
            'source_id'  => Source::create()->id,
        ];
    }
}
