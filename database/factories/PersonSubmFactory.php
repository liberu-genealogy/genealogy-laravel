<?php

namespace Database\Factories;

use App\Models\PersonSubm;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonSubmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonSubm::class;

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
            'subm' => $this->faker->word(),
        ];
    }
}
