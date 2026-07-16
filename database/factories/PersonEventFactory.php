<?php

namespace Database\Factories;

use App\Models\Addr;
use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonEvent>
 */
class PersonEventFactory extends Factory
{
    #[\Override]
    protected $model = PersonEvent::class;

    public function definition()
    {
        return [
            'person_id' => Person::factory()->create()->id,
            'addr_id' => Addr::factory()->create()->id,
            'places_id' => Place::factory()->create()->id,
            'title' => fake()->title(),
            'type' => fake()->word(),
            'attr' => fake()->text(),
            'date' => fake()->date(),
            'plac' => fake()->address(),
            'phon' => fake()->phoneNumber(),
            'caus' => fake()->text(),
            'age' => fake()->randomDigit(),
            'agnc' => fake()->word(),
            'description' => fake()->text(50),
            'year' => fake()->year(),
            'month' => fake()->month(),
            'day' => fake()->dayOfMonth(),
        ];
    }
}
