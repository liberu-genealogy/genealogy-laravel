<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Person::class, PersonResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $person = Person::factory()->create([
            'givn' => 'John',
            'surn' => 'Doe',
        ]);

        $this->assertDatabaseHas('people', ['givn' => 'John', 'surn' => 'Doe']);

        $retrieved = Person::find($person->id);
        $this->assertNotNull($retrieved);

        $person->update(['givn' => 'Jane']);
        $this->assertDatabaseHas('people', ['givn' => 'Jane']);

        $person->delete();
        $this->assertSoftDeleted('people', ['id' => $person->id]);
    }
}
