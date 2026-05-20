<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\PersonAliaResource;
use App\Models\PersonAlia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonAliaResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(PersonAlia::class, PersonAliaResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(PersonAliaResource::getNavigationLabel());
    }

    public function test_person_alia_can_be_created_in_database(): void
    {
        $personAlia = PersonAlia::factory()->create();

        $this->assertDatabaseHas('person_alia', ['id' => $personAlia->id]);
    }
}
