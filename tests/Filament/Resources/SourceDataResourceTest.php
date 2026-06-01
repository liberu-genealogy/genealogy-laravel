<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\SourceDataResource;
use App\Models\SourceData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceDataResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(SourceData::class, SourceDataResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = SourceDataResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = SourceData::factory()->create();

        $this->assertDatabaseHas('source_data', ['id' => $record->id]);

        $retrieved = SourceData::find($record->id);
        $this->assertNotNull($retrieved);

        $record->delete();
        $this->assertDatabaseMissing('source_data', ['id' => $record->id]);
    }
}
