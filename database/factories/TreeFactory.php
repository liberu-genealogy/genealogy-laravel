<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Tree;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tree::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'company_id' => Company::factory(),
            'current_tenant' => $this->faker->randomElement('1', '2'),
        ];
    }
}
