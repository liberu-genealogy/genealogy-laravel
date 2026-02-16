<?php

namespace Database\Factories;

use App\Models\Dna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dna>
 */
class DnaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dna::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'file_name' => 'dna-test-files/' . $this->faker->uuid . '.txt',
            'variable_name' => 'var_' . $this->faker->unique()->bothify('?????'),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
