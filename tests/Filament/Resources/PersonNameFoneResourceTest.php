<?php

namespace Tests\Filament\Resources;

use Tests\TestCase;
use App\Models\PersonNameFone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\PersonNameFoneResource\Pages\CreatePersonNameFone;
use App\Filament\Resources\PersonNameFoneResource\Pages\EditPersonNameFone;
use App\Filament\Resources\PersonNameFoneResource\Pages\ListPersonNameFones;

class PersonNameFoneResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePersonNameFone()
    {
        $formData = PersonNameFone::factory()->make()->toArray();
        $response = $this->post(route('filament.resources.person-name-fones.create'), $formData);
        $response->assertStatus(302); // Assuming redirect on success
        $this->assertDatabaseHas('person_name_fones', $formData);
    }

    public function testEditPersonNameFone()
    {
        $personNameFone = PersonNameFone::factory()->create();
        $updateData = ['name' => 'Updated Name'];
        $response = $this->put(route('filament.resources.person-name-fones.edit', ['record' => $personNameFone->id]), $updateData);
        $response->assertStatus(302); // Assuming redirect on success
        $this->assertDatabaseHas('person_name_fones', array_merge(['id' => $personNameFone->id], $updateData));
    }

    public function testListPersonNameFone()
    {
        $personNameFones = PersonNameFone::factory()->count(5)->create();
        $response = $this->get(route('filament.resources.person-name-fones.index'));
        $response->assertStatus(200);
        foreach ($personNameFones as $personNameFone) {
            $response->assertSee($personNameFone->name);
        }
    }

    public function testDeletePersonNameFone()
    {
        $personNameFone = PersonNameFone::factory()->create();
        $response = $this->delete(route('filament.resources.person-name-fones.delete', ['record' => $personNameFone->id]));
        $response->assertStatus(302); // Assuming redirect on success
        $this->assertDatabaseMissing('person_name_fones', ['id' => $personNameFone->id]);
    }
}
