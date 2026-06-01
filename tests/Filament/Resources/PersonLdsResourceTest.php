<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonLdsResource;
use App\Models\PersonLds;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonLdsResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(PersonLds::class, PersonLdsResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonLdsResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $personLds = PersonLds::factory()->create([
            'type' => 'BAPL',
        ]);

        $this->assertDatabaseHas('person_lds', ['type' => 'BAPL']);

        $retrieved = PersonLds::find($personLds->id);
        $this->assertNotNull($retrieved);

        $personLds->update(['type' => 'CONL']);
        $this->assertDatabaseHas('person_lds', ['type' => 'CONL']);

        $personLds->delete();
        $this->assertDatabaseMissing('person_lds', ['id' => $personLds->id]);
    }
}
