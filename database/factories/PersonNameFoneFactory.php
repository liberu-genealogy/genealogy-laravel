<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PersonNameFone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonNameFone>
 */
class PersonNameFoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = PersonNameFone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => fake()->word(),
            'gid' => fake()->randomElement(['1', '2']),
            'type' => fake()->word(),
            'name' => fake()->name(),
            'npfx' => fake()->word(),
            'givn' => fake()->firstName(),
            'nick' => fake()->userName(),
            'spfx' => '',
            'surn' => fake()->lastName(),
            'nsfx' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
