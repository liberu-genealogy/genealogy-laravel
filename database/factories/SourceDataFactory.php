<?php

namespace Database\Factories;

use App\Models\SourceData;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceData::class;

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
            'text' => $this->faker->text(50),
            'agnc' => $this->faker->word(),
        ];
    }
}
