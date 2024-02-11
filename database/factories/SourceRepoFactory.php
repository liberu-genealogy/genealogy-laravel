<?php

namespace Database\Factories;

use App\Models\Repository;
use App\Models\SourceRepo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceRepoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceRepo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => $this->faker->word(),
            'gid' => $this->faker->randomElement(['1', '2']),
            'repo_id' => Repository::where('id', 1)->first()->id,
            'caln' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
