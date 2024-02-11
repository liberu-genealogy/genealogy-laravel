<?php

namespace Database\Factories;

use App\Models\ImportJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportJobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImportJob::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomElement([1, 2]),
            'slug' => $this->faker->word(),
            'status' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
