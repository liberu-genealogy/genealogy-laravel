<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Person;
// use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'description' => $this->faker->text(),
            'is_active' => $this->faker->randomDigit(0, 1),
            'husband_id'=>Person::create()->id,
            'wife_id'=> Person::create()->id,
            // 'child_id'=> Person::create()->id,
            // 'type_id' => Type::create(['name' => $this->faker->name, 'description' => $this->faker->text])->id,
            'chan' => $this->faker->word(),
            'nchi' => $this->faker->word(),
            'rin' => $this->faker->word(),
        ];
    }
}
