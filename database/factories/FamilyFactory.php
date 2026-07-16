<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Family;
use App\Models\Person;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Family>
 */
class FamilyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = Family::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => fake()->text(),
            'is_active' => fake()->boolean(),
            'husband_id' => Person::factory(),
            'wife_id' => Person::factory(),
            'type_id' => Type::factory(),
            'chan' => fake()->word(),
            'nchi' => (string) fake()->randomDigit(),
            'rin' => fake()->word(),
        ];
    }
}
