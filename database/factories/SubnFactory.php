<?php

namespace Database\Factories;

use App\Models\Subn;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subn::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'subm' => $this->faker->word(),
            'famf' => $this->faker->word(),
            'temp' => $this->faker->word(),
            'ance' => $this->faker->word(),
            'desc' => $this->faker->randomDigit('0', '1'),
            'ordi' => $this->faker->word(),
            'rin' => $this->faker->word(),
        ];
    }
}
