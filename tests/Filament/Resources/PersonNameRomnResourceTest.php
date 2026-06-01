<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonNameRomnResource;
use App\Models\PersonNameRomn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonNameRomnResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(PersonNameRomn::class, PersonNameRomnResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonNameRomnResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = PersonNameRomn::factory()->create([
            'name' => 'Test Romanized Name',
        ]);

        $this->assertDatabaseHas('person_name_romn', ['name' => 'Test Romanized Name']);

        $retrieved = PersonNameRomn::find($record->id);
        $this->assertNotNull($retrieved);

        $record->update(['name' => 'Updated Name']);
        $this->assertDatabaseHas('person_name_romn', ['name' => 'Updated Name']);

        $record->delete();
        $this->assertDatabaseMissing('person_name_romn', ['id' => $record->id]);
    }
}
