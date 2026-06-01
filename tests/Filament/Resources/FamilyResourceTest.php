<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\FamilyResource;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Family::class, FamilyResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = FamilyResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $family = Family::factory()->create();

        $this->assertDatabaseHas('families', ['id' => $family->id]);

        $retrieved = Family::find($family->id);
        $this->assertNotNull($retrieved);

        $family->update(['nchi' => '2']);
        $this->assertDatabaseHas('families', ['id' => $family->id, 'nchi' => '2']);

        $family->delete();
        $this->assertDatabaseMissing('families', ['id' => $family->id]);
    }
}
