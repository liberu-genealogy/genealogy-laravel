<?php

namespace Tests\Filament\Resources;

use App\Models\PersonAnci;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonAnciResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreatePersonAnci(): void
    {
        $data = [
            'group' => $this->faker->word,
            'gid'   => $this->faker->randomNumber(),
            'anci'  => $this->faker->word,
        ];

        $response = $this->post(route('filament.resources.person-anci.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_ancis', $data);
    }

    public function testReadPersonAnci(): void
    {
        $personAnci = PersonAnci::factory()->create();

        $response = $this->get(route('filament.resources.person-anci.index'));

        $response->assertStatus(200);
        $response->assertSee($personAnci->group);
    }

    public function testUpdatePersonAnci(): void
    {
        $personAnci = PersonAnci::factory()->create();

        $updatedData = [
            'group' => 'Updated Group',
            'gid'   => $personAnci->gid + 1,
            'anci'  => 'Updated Anci',
        ];

        $response = $this->put(route('filament.resources.person-anci.update', $personAnci), $updatedData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_ancis', $updatedData);
    }

    public function testDeletePersonAnci(): void
    {
        $personAnci = PersonAnci::factory()->create();

        $response = $this->delete(route('filament.resources.person-anci.destroy', $personAnci));

        $response->assertStatus(302);
        $this->assertSoftDeleted($personAnci);
    }
}