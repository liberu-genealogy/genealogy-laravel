<?php

namespace Database\Factories;

use App\Models\MediaObjectFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MediaObjectFile>
 */
class MediaObjectFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaObjectFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group'      => fake()->word(),
            'gid'        => fake()->randomElement(['1', '2']),
            'form'       => fake()->word(),
            'medi'       => fake()->word(),
            'type'       => fake()->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
