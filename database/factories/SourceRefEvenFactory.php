<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\SourceRefEven;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceRefEvenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceRefEven::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => $this->faker->word(),
            'gid' => $this->faker->randomDigit('1', '2'),
            'even' => $this->faker->word(),
            'role' => $this->faker->word(),
        ];
    }
}
