<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonEvent>
 */
class PersonEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'person_id' => Person::create([
                'name'  => fake()->name(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
            ])->id,
            'title'     => fake()->title(),
            'type'      => fake()->word(),
            'attr'      => fake()->text(),
            'date'      => fake()->date(),
            'plac'      => fake()->address(),
            'phon'      => fake()->phoneNumber(),
            'caus'      => fake()->text(),
            'age'       => fake()->randomDigit(),
            'agnc'      => fake()->word(),
            'places_id' => Place::create([
                'description' => fake()->text(50),
                'title'       => fake()->word(),
                'date'        => fake()->date(),
            ])->id,
            'description' => fake()->text(50),
            'year'        => fake()->year(),
            'month'       => fake()->month(),
            'day'         => fake()->dayOfMonth(),
        ];
    }
}
