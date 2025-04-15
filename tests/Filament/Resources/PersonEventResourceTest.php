<?php

namespace Tests\Filament\Resources;

use App\Models\PersonEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonEventResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreatePersonEvent(): void
    {
        $data = [
            'converted_date' => $this->faker->date,
            'year'           => $this->faker->year,
            'month'          => $this->faker->month,
            'day'            => $this->faker->dayOfMonth,
            'type'           => $this->faker->word,
            'attr'           => $this->faker->sentence,
            'plac'           => $this->faker->city,
            'addr_id'        => $this->faker->randomNumber(),
            'phon'           => $this->faker->phoneNumber,
            'caus'           => $this->faker->sentence,
            'age'            => $this->faker->randomDigitNotNull,
            'agnc'           => $this->faker->company,
            'adop'           => $this->faker->word,
            'adop_famc'      => $this->faker->word,
            'birt_famc'      => $this->faker->word,
            'person_id'      => $this->faker->randomNumber(),
            'title'          => $this->faker->sentence,
            'date'           => $this->faker->date,
            'description'    => $this->faker->sentence,
            'places_id'      => $this->faker->randomNumber(),
        ];

        $response = $this->post(route('filament.resources.person-events.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_events', $data);
    }

    public function testReadPersonEvent(): void
    {
        $personEvent = PersonEvent::factory()->create();

        $response = $this->get(route('filament.resources.person-events.index'));

        $response->assertStatus(200);
        $response->assertSee([$personEvent->type, $personEvent->date]);
    }

    public function testUpdatePersonEvent(): void
    {
        $personEvent = PersonEvent::factory()->create();

        $updatedData = [
            'type' => 'Updated Type',
            'date' => $this->faker->date,
        ];

        $response = $this->put(route('filament.resources.person-events.update', $personEvent), $updatedData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_events', $updatedData);
    }

    public function testDeletePersonEvent(): void
    {
        $personEvent = PersonEvent::factory()->create();

        $response = $this->delete(route('filament.resources.person-events.destroy', $personEvent));

        $response->assertStatus(302);
        $this->assertSoftDeleted('person_events', ['id' => $personEvent->id]);
    }
}
