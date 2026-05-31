<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonAssoResource;
use App\Models\PersonAsso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonAssoResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(PersonAsso::class, PersonAssoResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonAssoResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $personAsso = PersonAsso::factory()->create([
            'rela' => 'Test Relation',
        ]);

        $this->assertDatabaseHas('person_asso', ['rela' => 'Test Relation']);

        $retrieved = PersonAsso::find($personAsso->id);
        $this->assertNotNull($retrieved);

        $personAsso->update(['rela' => 'Updated Relation']);
        $this->assertDatabaseHas('person_asso', ['rela' => 'Updated Relation']);

        $personAsso->delete();
        $this->assertSoftDeleted('person_asso', ['id' => $personAsso->id]);
    }
}
