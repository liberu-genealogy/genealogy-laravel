<?php

namespace Database\Factories;

use App\Models\PersonNameFone;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonNameFoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonNameFone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => $this->faker->word(), 'gid' => $this->faker->randomElement('1', '2'),
            'type' => $this->faker->word(), 'name' => $this->faker->name(),
            'npfx' => $this->faker->word(), 'givn' => $this->faker->firstName(),
            'nick' => $this->faker->userName(), 'spfx', 'surn' => $this->faker->lastName(),  'nsfx', 'created_at', 'updated_at',
        ];
    }
}
