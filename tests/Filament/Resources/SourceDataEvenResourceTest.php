<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\SourceDataEvenResource;
use App\Models\SourceDataEven;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceDataEvenResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(SourceDataEven::class, SourceDataEvenResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = SourceDataEvenResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = SourceDataEven::factory()->create();

        $this->assertDatabaseHas('source_data_even', ['id' => $record->id]);

        $retrieved = SourceDataEven::find($record->id);
        $this->assertNotNull($retrieved);

        $record->delete();
        $this->assertDatabaseMissing('source_data_even', ['id' => $record->id]);
    }
}
