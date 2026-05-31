<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonNameFoneResource;
use App\Models\PersonNameFone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonNameFoneResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(PersonNameFone::class, PersonNameFoneResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonNameFoneResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = PersonNameFone::factory()->create([
            'type' => 'FONE',
        ]);

        $this->assertDatabaseHas('person_name_fone', ['type' => 'FONE']);

        $retrieved = PersonNameFone::find($record->id);
        $this->assertNotNull($retrieved);

        $record->update(['type' => 'UPDATED']);
        $this->assertDatabaseHas('person_name_fone', ['type' => 'UPDATED']);

        $record->delete();
        $this->assertDatabaseMissing('person_name_fone', ['id' => $record->id]);
    }
}
