<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\SourceResource;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Source::class, SourceResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = SourceResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = Source::factory()->create([
            'name' => 'Test Source',
        ]);

        $this->assertDatabaseHas('sources', ['name' => 'Test Source']);

        $retrieved = Source::find($record->id);
        $this->assertNotNull($retrieved);

        $record->update(['name' => 'Updated Source']);
        $this->assertDatabaseHas('sources', ['name' => 'Updated Source']);

        $record->delete();
        $this->assertDatabaseMissing('sources', ['id' => $record->id]);
    }
}
