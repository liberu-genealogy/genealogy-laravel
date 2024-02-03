<?php

namespace Database\Factories;

use App\Models\MediaObjectFile;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'group' => $this->faker->word(), 'gid' => $this->faker->randomElement('1', '2'),
            'form' => $this->faker->word(), 'medi' => $this->faker->word(), 'type' => $this->faker->word(), 'created_at', 'updated_at',
        ];
    }
}
