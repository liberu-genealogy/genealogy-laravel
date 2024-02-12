<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use LaravelLiberu\Companies\Database\Factories\CompanyFactory as CoreCompanyFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'privacy' => $this->faker->word(),
            'name' => $this->faker->word(),
            'email' => $this->faker->email(),
            'is_tenant' => $this->faker->boolean(),
            'status' => $this->faker->randomElement([1,2,3,4]),
            'created_by' => User::where('id', $this->faker->randomElement([1,2,3,4]))->first()->id,
            'updated_by' => User::where('id', $this->faker->randomElement([1,2,3,4]))->first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
