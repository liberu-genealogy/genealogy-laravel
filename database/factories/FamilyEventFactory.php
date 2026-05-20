<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FamilyEvent>
 */
class FamilyEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FamilyEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'family_id'   => Family::create()->id,
            'places_id'   => Place::create(['title'=> fake()->title])->id,
            'date'        => fake()->date(),
            'title'       => fake()->word(),
            'description' => fake()->text(50),
            'year'        => fake()->year(),
            'month'       => fake()->month(),
            'day'         => fake()->dayOfMonth(),
            'type'        => fake()->word(),
            'plac'        => fake()->word(),
            'phon'        => fake()->phoneNumber(),
            'caus'        => fake()->word(),
            'age'         => fake()->numberBetween(10, 79),
            'husb'        => fake()->numberBetween(1, 100),
            'wife'        => fake()->numberBetween(1, 100),
        ];
    }
}
