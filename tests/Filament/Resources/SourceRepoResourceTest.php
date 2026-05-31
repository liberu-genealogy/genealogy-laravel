<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\SourceRepoResource;
use App\Models\SourceRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceRepoResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(SourceRepo::class, SourceRepoResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = SourceRepoResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = SourceRepo::factory()->create();

        $this->assertDatabaseHas('source_repo', ['id' => $record->id]);

        $retrieved = SourceRepo::find($record->id);
        $this->assertNotNull($retrieved);

        $record->delete();
        $this->assertDatabaseMissing('source_repo', ['id' => $record->id]);
    }
}
