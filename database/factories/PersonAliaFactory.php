<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PersonAlia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonAlia>
 */
class PersonAliaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = PersonAlia::class;

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
            'alia' => fake()->word(),
            'import_confirm' => fake()->randomDigit(),
        ];
    }
}
