<?php

namespace Database\Factories;

use App\Models\Citation;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Citation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'date' => $this->faker->date,
            'description' => $this->faker->text(50),
            // 'repository_id' => Repository::create()->id,
            'volume' => $this->faker->randomDigit(1, 2, 3, 4, 5),
            'page' => $this->faker->randomDigit(500),
            'is_active' =>$this->faker->randomDigit(0, 1),
            'confidence' => $this->faker->word,
            'source_id' => Source::create()->id,

        ];
    }
}
