<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\FamilySlgsResource;
use App\Models\FamilySlgs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilySlgsResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(FamilySlgs::class, FamilySlgsResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = FamilySlgsResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $familySlgs = FamilySlgs::factory()->create([
            'stat' => 'COMPLETED',
        ]);

        $this->assertDatabaseHas('family_slgs', ['stat' => 'COMPLETED']);

        $retrieved = FamilySlgs::find($familySlgs->id);
        $this->assertNotNull($retrieved);

        $familySlgs->update(['stat' => 'PENDING']);
        $this->assertDatabaseHas('family_slgs', ['stat' => 'PENDING']);

        $familySlgs->delete();
        $this->assertDatabaseMissing('family_slgs', ['id' => $familySlgs->id]);
    }
}
