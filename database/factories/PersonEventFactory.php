<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

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
                'name' => $this->faker->name(),
                'email' => $this->faker->email(),
                'phone' => $this->faker->phoneNumber(),
            ])->id,
            'title' => $this->faker->title(),
            'type' => $this->faker->word(),
            'attr' => $this->faker->text(),
            'date' => $this->faker->date(),
            'plac' => $this->faker->address(),
            'phon' => $this->faker->phoneNumber(),
            'caus' => $this->faker->text(),
            'age' => $this->faker->randomDigit(1, 50),
            'agnc' => $this->faker->word(),
            'places_id' => Place::create([
                'description' => $this->faker->text(50),
                'title' => $this->faker->word(),
                'date' => $this->faker->date(),
            ])->id,
            'description' => $this->faker->text(50),
            'year' => $this->faker->year(),
            'month' => $this->faker->month(),
            'day' => $this->faker->dayOfMonth(),
        ];
    }
}
