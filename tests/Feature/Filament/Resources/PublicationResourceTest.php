<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\PublicationResource;
use App\Models\Publication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Publication::class, PublicationResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(PublicationResource::getNavigationLabel());
    }

    public function test_publication_can_be_created_in_database(): void
    {
        $publication = Publication::factory()->create();

        $this->assertDatabaseHas('publications', ['id' => $publication->id]);
    }
}
