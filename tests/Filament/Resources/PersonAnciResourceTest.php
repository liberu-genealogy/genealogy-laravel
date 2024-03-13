<?php

namespace Tests\Filament\Resources;

use Tests\TestCase;
use App\Models\PersonAnci;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PersonAnciResourceTest extends TestCase
{
/**
 * Tests for the PersonAnciResource.
 * This class provides automated tests for CRUD operations and resource visibility within the Filament admin panel.
 */
    use RefreshDatabase, WithFaker;

    public function testCreatePersonAnci()
    {
        $data = [
            'group' => $this->faker->word,
            'gid' => $this->faker->randomNumber(),
            'anci' => $this->faker->word,
        ];

        $response = $this->post(route('filament.resources.person-anci.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_ancis', $data);
    }

    public function testReadPersonAnci()
    /**
     * Test the creation of a PersonAnci resource.
     */
    {
        $personAnci = PersonAnci::factory()->create();

        $response = $this->get(route('filament.resources.person-anci.index'));

        $response->assertStatus(200);
        $response->assertSee($personAnci->group);
    }

    public function testUpdatePersonAnci()
    /**
     * Test the retrieval of a PersonAnci resource.
     */
    {
        $personAnci = PersonAnci::factory()->create();

        $updatedData = [
            'group' => 'Updated Group',
            'gid' => $personAnci->gid + 1,
            'anci' => 'Updated Anci',
        ];

        $response = $this->put(route('filament.resources.person-anci.update', $personAnci), $updatedData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_ancis', $updatedData);
    }

    public function testDeletePersonAnci()
    /**
     * Test the update functionality for a PersonAnci resource.
     */
    {
        $personAnci = PersonAnci::factory()->create();

        $response = $this->delete(route('filament.resources.person-anci.destroy', $personAnci));

        $response->assertStatus(302);
        $this->assertSoftDeleted($personAnci);
    }
}
    /**
     * Test the deletion of a PersonAnci resource.
     */
