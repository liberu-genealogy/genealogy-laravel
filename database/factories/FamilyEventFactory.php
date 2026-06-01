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
    #[\Override]
    protected $model = FamilyEvent::class;

    public function definition()
    {
        return [
            'family_id'   => Family::factory()->create()->id,
            'places_id'   => Place::factory()->create()->id,
            'date'        => fake()->date(),
            'title'       => fake()->word(),
            'description' => fake()->text(50),
        ];
    }
}
