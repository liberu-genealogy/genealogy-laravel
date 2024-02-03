<?php

namespace Database\Factories;

use App\Models\MediaObject;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'group' => $this->faker->word(),
            'gid' => $this->faker->randomDigit('1', '2'),
            'titl' => $this->faker->word(),
            'obje_id' => $this->faker->randomDigit('1', '2'),
            'rin' => $this->faker->word(),
        ];
    }
}
