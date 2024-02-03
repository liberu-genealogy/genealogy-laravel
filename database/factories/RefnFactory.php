<?php

namespace Database\Factories;

use App\Models\Refn;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Refn::class;

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
            'refn' => $this->faker->word(),
            'type' => $this->faker->word(),
        ];
    }
}
