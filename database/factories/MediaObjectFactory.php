<?php

namespace Database\Factories;

use App\Models\MediaObject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MediaObject>
 */
class MediaObjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaObject::class;

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
            'titl'    => fake()->word(),
            'obje_id' => fake()->randomDigit(),
            'rin'     => fake()->word(),
        ];
    }
}
