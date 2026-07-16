<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\CitationResource;
use App\Models\Citation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Citation::class, CitationResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = CitationResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $citation = Citation::factory()->create([
            'name' => 'Test Citation',
            'confidence' => 5,
        ]);

        $this->assertDatabaseHas('citations', ['name' => 'Test Citation']);

        $retrieved = Citation::find($citation->id);
        $this->assertNotNull($retrieved);

        $citation->update(['name' => 'Updated Citation']);
        $this->assertDatabaseHas('citations', ['name' => 'Updated Citation']);

        $citation->delete();
        $this->assertDatabaseMissing('citations', ['id' => $citation->id]);
    }
}
