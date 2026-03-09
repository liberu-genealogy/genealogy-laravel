<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\PlaceResource;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaceResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Place::class, PlaceResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(PlaceResource::getNavigationLabel());
    }

    public function test_place_can_be_created_in_database(): void
    {
        $place = Place::factory()->create();

        $this->assertDatabaseHas('places', ['id' => $place->id]);
    }
}
