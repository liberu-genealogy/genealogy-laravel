<?php

namespace Database\Factories;

use App\Models\PersonNameRomn;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonNameRomnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonNameRomn::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'group' => $this->faker->word(), 'gid' => $this->faker->randomElement('1', '2'),
            'type' => $this->faker->word(), 'name' => $this->faker->name(),
            'npfx' => $this->faker->word(), 'givn' => $this->faker->firstName(),
            'nick' => $this->faker->userName(), 'spfx', 'surn' => $this->faker->lastName(),  'nsfx', 'created_at', 'updated_at',
        ];
    }
}
