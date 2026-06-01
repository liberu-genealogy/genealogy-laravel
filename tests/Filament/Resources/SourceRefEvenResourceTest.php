<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\SourceRefEvenResource;
use App\Models\SourceRefEven;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceRefEvenResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(SourceRefEven::class, SourceRefEvenResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = SourceRefEvenResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $record = SourceRefEven::factory()->create();

        $this->assertDatabaseHas('sourceref_even', ['id' => $record->id]);

        $retrieved = SourceRefEven::find($record->id);
        $this->assertNotNull($retrieved);

        $record->delete();
        $this->assertDatabaseMissing('sourceref_even', ['id' => $record->id]);
    }
}
