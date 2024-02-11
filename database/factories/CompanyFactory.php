<?php

namespace Database\Factories;

use App\Models\Company;
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
        ];
    }
}
