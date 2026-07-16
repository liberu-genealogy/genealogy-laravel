<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PersonSubm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonSubm>
 */
class PersonSubmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = PersonSubm::class;

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
            'subm' => fake()->word(),
        ];
    }
}
