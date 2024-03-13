<?php

namespace Tests\Filament\Resources;

use Tests\TestCase;
use App\Models\PersonNameRomn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\PersonNameRomnResource\Pages\CreatePersonNameRomn;
use App\Filament\Resources\PersonNameRomnResource\Pages\EditPersonNameRomn;
use App\Filament\Resources\PersonNameRomnResource\Pages\ListPersonNameRomns;
use Illuminate\Foundation\Testing\WithFaker;

class PersonNameRomnResourceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreatePersonNameRomn()
    {
        $formData = PersonNameRomn::factory()->make()->toArray();
        $response = $this->post(route('filament.resources.person-name-romns.create'), $formData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('person_name_romns', $formData);
    }

    public function testEditPersonNameRomn()
    {
        $personNameRomn = PersonNameRomn::factory()->create();
        $updateData = ['name' => $this->faker->name];
        $response = $this->put(route('filament.resources.person-name-romns.edit', ['record' => $personNameRomn->id]), $updateData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('person_name_romns', array_merge(['id' => $personNameRomn->id], $updateData));
    }

    public function testListPersonNameRomn()
    {
        $personNameRomns = PersonNameRomn::factory()->count(5)->create();
        $response = $this->get(route('filament.resources.person-name-romns.index'));
        $response->assertStatus(200);
        foreach ($personNameRomns as $personNameRomn) {
            $response->assertSee($personNameRomn->name, false);
        }
    }

    public function testDeletePersonNameRomn()
    {
        $personNameRomn = PersonNameRomn::factory()->create();
        $response = $this->delete(route('filament.resources.person-name-romns.delete', ['record' => $personNameRomn->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('person_name_romns', ['id' => $personNameRomn->id]);
    }
}
