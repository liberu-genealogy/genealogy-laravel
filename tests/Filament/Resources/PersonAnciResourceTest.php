<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonAnciResource;
use App\Models\PersonAnci;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonAnciResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(PersonAnci::class, PersonAnciResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonAnciResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $personAnci = PersonAnci::factory()->create([
            'anci' => 'Test Anci',
        ]);

        $this->assertDatabaseHas('person_anci', ['anci' => 'Test Anci']);

        $retrieved = PersonAnci::find($personAnci->id);
        $this->assertNotNull($retrieved);

        $personAnci->update(['anci' => 'Updated Anci']);
        $this->assertDatabaseHas('person_anci', ['anci' => 'Updated Anci']);

        $personAnci->delete();
        $this->assertDatabaseMissing('person_anci', ['id' => $personAnci->id]);
    }
}
