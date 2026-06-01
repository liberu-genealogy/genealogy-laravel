<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\FamilyEventResource;
use App\Models\FamilyEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyEventResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(FamilyEvent::class, FamilyEventResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = FamilyEventResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $event = FamilyEvent::factory()->create([
            'title' => 'Test Event',
        ]);

        $this->assertDatabaseHas('family_events', ['title' => 'Test Event']);

        $retrieved = FamilyEvent::find($event->id);
        $this->assertNotNull($retrieved);

        $event->update(['title' => 'Updated Event']);
        $this->assertDatabaseHas('family_events', ['title' => 'Updated Event']);

        $event->delete();
        $this->assertSoftDeleted('family_events', ['id' => $event->id]);
    }
}
