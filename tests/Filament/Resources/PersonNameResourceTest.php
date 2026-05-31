<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonNameResource;
use App\Models\PersonName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonNameResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(PersonName::class, PersonNameResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonNameResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = PersonName::factory()->create([
            'name' => 'Test Name',
        ]);

        $this->assertDatabaseHas('person_name', ['name' => 'Test Name']);

        $retrieved = PersonName::find($record->id);
        $this->assertNotNull($retrieved);

        $record->update(['name' => 'Updated Name']);
        $this->assertDatabaseHas('person_name', ['name' => 'Updated Name']);

        $record->delete();
        $this->assertDatabaseMissing('person_name', ['id' => $record->id]);
    }
}
