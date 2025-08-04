<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Person;
use App\Models\Type;
// use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
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
            'is_active'   => fake()->randomDigit(),
            'husband_id'  => fake()->randomDigit(),
            'wife_id'     => fake()->randomDigit(),
            // 'child_id'=> Person::create()->id,
            'type_id' => Type::where('id', 1)->first(),
            'chan'    => fake()->word(),
            'nchi'    => fake()->word(),
            'rin'     => fake()->word(),
        ];
    }
}
