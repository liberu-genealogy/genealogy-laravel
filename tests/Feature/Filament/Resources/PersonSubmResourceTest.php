<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\PersonSubmResource;
use App\Models\PersonSubm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonSubmResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(PersonSubm::class, PersonSubmResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(PersonSubmResource::getNavigationLabel());
    }

    public function test_person_subm_can_be_created_in_database(): void
    {
        $personSubm = PersonSubm::factory()->create();

        $this->assertDatabaseHas('person_subm', ['id' => $personSubm->id]);
    }
}
