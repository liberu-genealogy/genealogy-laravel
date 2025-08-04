<?php

namespace Database\Factories;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'         => fake()->name,
            'appellative'  => fake()->firstName,
            'email'        => fake()->unique()->safeEmail,
            'phone'        => fake()->phoneNumber,
            'birthday'     => Carbon::now()->subYears(rand(15, 40)),
            'bank'         => fake()->word,
            'bank_account' => fake()->bankAccountNumber,
        ];
    }
}
