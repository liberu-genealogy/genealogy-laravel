<?php

namespace Database\Factories;

use App\Models\Source;
use App\Models\SourceRef;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SourceRef>
 */
class SourceRefFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceRef::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group'   => fake()->word(),
            'gid'     => fake()->randomDigit(),
            'sour_id' => Source::create()->id,
            'text'    => fake()->word(),
            'quay'    => fake()->word(),
            'page'    => fake()->word(),
        ];
    }
}
