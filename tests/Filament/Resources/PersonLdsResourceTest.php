<?php

namespace Tests\Filament\Resources;

use App\Models\PersonLds;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonLdsResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreatePersonLds()
    {
        $data = [
            'group'     => $this->faker->word,
            'gid'       => $this->faker->randomNumber(),
            'type'      => $this->faker->word,
            'stat'      => $this->faker->word,
            'date'      => $this->faker->date,
            'plac'      => $this->faker->city,
            'temp'      => $this->faker->word,
            'slac_famc' => $this->faker->word,
        ];

        $response = $this->post(route('filament.resources.person-lds.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_lds', $data);
    }

    public function testReadPersonLds()
    {
        $personLds = PersonLds::factory()->create();

        $response = $this->get(route('filament.resources.person-lds.index'));

        $response->assertStatus(200);
        $response->assertSee([$personLds->group, $personLds->type]);
    }

    public function testUpdatePersonLds()
    {
        $personLds = PersonLds::factory()->create();

        $updatedData = [
            'group' => 'Updated Group',
            'type'  => 'Updated Type',
        ];

        $response = $this->put(route('filament.resources.person-lds.update', $personLds), $updatedData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_lds', $updatedData);
    }

    public function testDeletePersonLds()
    {
        $personLds = PersonLds::factory()->create();

        $response = $this->delete(route('filament.resources.person-lds.destroy', $personLds));

        $response->assertStatus(302);
        $this->assertSoftDeleted('person_lds', ['id' => $personLds->id]);
    }
}
