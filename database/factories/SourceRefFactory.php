<?php

namespace Database\Factories;

use App\Models\Source;
use App\Models\SourceRef;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceRefFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SourceRef::class;

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
            'sour_id' => Source::create()->id,
            'text' => $this->faker->word(),
            'quay' => $this->faker->word(),
            'page' => $this->faker->word(),
        ];
    }
}
