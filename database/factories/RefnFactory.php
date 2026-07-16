<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Refn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Refn>
 */
class RefnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = Refn::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => fake()->word(),
            'gid' => fake()->randomDigit(),
            'refn' => fake()->word(),
            'type' => fake()->word(),
        ];
    }
}
