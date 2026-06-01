<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\SourceRefResource;
use App\Models\SourceRef;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceRefResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(SourceRef::class, SourceRefResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = SourceRefResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = SourceRef::factory()->create();

        $this->assertDatabaseHas('source_ref', ['id' => $record->id]);

        $retrieved = SourceRef::find($record->id);
        $this->assertNotNull($retrieved);

        $record->delete();
        $this->assertDatabaseMissing('source_ref', ['id' => $record->id]);
    }
}
