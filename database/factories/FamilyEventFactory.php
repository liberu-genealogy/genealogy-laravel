<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\Place;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'family_id'=> Family::create()->id,
            'places_id' => Place::create(['title'=> $this->faker->title])->id,
            'date' => $this->faker->date(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(50),
            'year' => $this->faker->year(),
            'month' => $this->faker->month(),
            'day' => $this->faker->dayOfMonth(),
            'type' => $this->faker->word(),
            'plac'  => $this->faker->word(),
            'phon' => $this->faker->phoneNumber(),
            'caus'  => $this->faker->word(),
            'age' => $this->faker->numberBetween(10, 79),
            'husb' => $this->faker->word(),
            'wife' => $this->faker->word(),
        ];
    }
}
