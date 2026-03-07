<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\MediaObjectResource;
use App\Models\MediaObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaObjectResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(MediaObject::class, MediaObjectResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(MediaObjectResource::getNavigationLabel());
    }

    public function test_media_object_can_be_created_in_database(): void
    {
        $mediaObject = MediaObject::factory()->create();

        $this->assertDatabaseHas('media_objects', ['id' => $mediaObject->id]);
    }
}
