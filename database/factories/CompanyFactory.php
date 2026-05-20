<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'privacy'    => fake()->word(),
            'name'       => fake()->word(),
            'email'      => fake()->email(),
            'status'     => fake()->randomElement([1, 2, 3, 4]),
            'created_by' => User::where('id', fake()->randomElement([1, 2, 3, 4]))->first()->id,
            'updated_by' => User::where('id', fake()->randomElement([1, 2, 3, 4]))->first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
