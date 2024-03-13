<?php

namespace Tests\Filament\Resources;

use Tests\TestCase;
use App\Models\PersonName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\PersonNameResource\Pages\CreatePersonName;
use App\Filament\Resources\PersonNameResource\Pages\EditPersonName;
use App\Filament\Resources\PersonNameResource\Pages\ListPersonNames;

class PersonNameResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePersonName()
    {
        $formData = PersonName::factory()->make()->toArray();
        $response = $this->post(route('filament.resources.person-names.create'), $formData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('person_names', $formData);
    }

    public function testEditPersonName()
    {
        $personName = PersonName::factory()->create();
        $updateData = ['name' => 'Updated Name'];
        $response = $this->put(route('filament.resources.person-names.edit', ['record' => $personName->id]), $updateData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('person_names', array_merge(['id' => $personName->id], $updateData));
    }

    public function testListPersonName()
    {
        $personNames = PersonName::factory()->count(5)->create();
        $response = $this->get(route('filament.resources.person-names.index'));
        $response->assertStatus(200);
        foreach ($personNames as $personName) {
            $response->assertSee($personName->name);
        }
    }

    public function testDeletePersonName()
    {
        $personName = PersonName::factory()->create();
        $response = $this->delete(route('filament.resources.person-names.delete', ['record' => $personName->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('person_names', ['id' => $personName->id]);
    }
}
