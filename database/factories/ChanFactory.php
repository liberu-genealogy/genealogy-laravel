<?php

namespace Database\Factories;

use App\Models\Chan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'gid' => $this->faker->randomDigit(1, 2),
            'group' => $this->faker->text(50),
            'date' => $this->faker->date,
            'time' => $this->faker->time,
        ];
    }
}
