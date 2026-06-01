<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonEventResource;
use App\Models\PersonEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonEventResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(PersonEvent::class, PersonEventResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonEventResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $event = PersonEvent::factory()->create([
            'type' => 'BIRT',
        ]);

        $this->assertDatabaseHas('person_events', ['type' => 'BIRT']);

        $retrieved = PersonEvent::find($event->id);
        $this->assertNotNull($retrieved);

        $event->update(['type' => 'DEAT']);
        $this->assertDatabaseHas('person_events', ['type' => 'DEAT']);

        $event->delete();
        $this->assertSoftDeleted('person_events', ['id' => $event->id]);
    }
}
