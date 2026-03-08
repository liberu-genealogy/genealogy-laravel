<?php

namespace Tests\Feature\Filament;

use App\Filament\App\Resources\SubmResource;
use App\Models\Subm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Subm::class, SubmResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(SubmResource::getNavigationLabel());
    }

    public function test_subm_can_be_created_in_database(): void
    {
        $subm = Subm::factory()->create();

        $this->assertDatabaseHas('subms', ['id' => $subm->id]);
    }
}
