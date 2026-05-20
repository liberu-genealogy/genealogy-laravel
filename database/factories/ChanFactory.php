<?php

namespace Database\Factories;

use App\Models\Chan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chan>
 */
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
            'gid'   => fake()->randomDigit(),
            'group' => fake()->text(50),
            'date'  => fake()->date,
            'time'  => fake()->time,
        ];
    }
}
