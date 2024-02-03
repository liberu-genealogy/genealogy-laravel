<?php

namespace Database\Factories;

use App\Models\SourceDataEven;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceDataEvenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceDataEven::class;

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
            'date' => $this->faker->date(),
            'plac' => $this->faker->word(),
        ];
    }
}
