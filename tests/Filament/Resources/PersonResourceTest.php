<?php

namespace Tests\Filament\Resources;

use Tests\TestCase;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\PersonResource\Pages\CreatePerson;
use App\Filament\Resources\PersonResource\Pages\EditPerson;
use App\Filament\Resources\PersonResource\Pages\ListPersons;

class PersonResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePerson()
    {
        $formData = Person::factory()->make()->toArray();
        $response = $this->post(route('filament.resources.persons.create'), $formData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('persons', $formData);
    }

    public function testEditPerson()
    {
        $person = Person::factory()->create();
        $updateData = ['name' => 'Updated Name', 'email' => 'updated@example.com'];
        $response = $this->put(route('filament.resources.persons.edit', ['record' => $person->id]), $updateData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('persons', array_merge(['id' => $person->id], $updateData));
    }

    public function testListPerson()
    {
        $persons = Person::factory()->count(5)->create();
        $response = $this->get(route('filament.resources.persons.index'));
        $response->assertStatus(200);
        foreach ($persons as $person) {
            $response->assertSee($person->name, false);
            $response->assertSee($person->email, false);
        }
    }

    public function testDeletePerson()
    {
        $person = Person::factory()->create();
        $response = $this->delete(route('filament.resources.persons.delete', ['record' => $person->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('persons', ['id' => $person->id]);
    }
}
