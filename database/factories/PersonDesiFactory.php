<?php

namespace Database\Factories;

use App\Models\PersonDesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonDesiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonDesi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group' => $this->faker->word(), 'gid' => $this->faker->randomElement('1', '2'),
            'desi' =>$this->faker->word(), , 'created_at', 'updated_at',
        ];
    }
}
