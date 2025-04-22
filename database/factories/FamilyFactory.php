<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Person;
use App\Models\Type;
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
            'is_active'   => $this->faker->randomDigit(0, 1),
            'husband_id'  => $this->faker->randomDigit(1, 2),
            'wife_id'     => $this->faker->randomDigit(1, 2),
            // 'child_id'=> Person::create()->id,
            'type_id' => Type::where('id', 1)->first(),
            'chan'    => $this->faker->word(),
            'nchi'    => $this->faker->word(),
            'rin'     => $this->faker->word(),
        ];
    }
}
