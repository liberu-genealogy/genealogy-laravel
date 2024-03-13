<?php

namespace Tests\Filament\Resources;

use App\Models\PersonAsso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonAssoResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreatePersonAsso()
    {
        $data = [
            'group'          => $this->faker->word,
            'gid'            => $this->faker->randomNumber(),
            'indi'           => $this->faker->word,
            'rela'           => $this->faker->word,
            'import_confirm' => 1,
        ];

        $response = $this->post(route('filament.resources.person-asso.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_assos', $data);
    }

    public function testReadPersonAsso()
    {
        $personAsso = PersonAsso::factory()->create();

        $response = $this->get(route('filament.resources.person-asso.index'));

        $response->assertStatus(200);
        $response->assertSee([$personAsso->group, $personAsso->indi]);
    }

    public function testUpdatePersonAsso()
    {
        $personAsso = PersonAsso::factory()->create();

        $updatedData = [
            'group'          => 'Updated Group',
            'gid'            => $personAsso->gid + 1,
            'indi'           => 'Updated Indi',
            'rela'           => 'Updated Rela',
            'import_confirm' => 0,
        ];

        $response = $this->put(route('filament.resources.person-asso.update', $personAsso), $updatedData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('person_assos', $updatedData);
    }

    public function testDeletePersonAsso()
    {
        $personAsso = PersonAsso::factory()->create();

        $response = $this->delete(route('filament.resources.person-asso.destroy', $personAsso));

        $response->assertStatus(302);
        $this->assertSoftDeleted('person_assos', ['id' => $personAsso->id]);
    }
}
