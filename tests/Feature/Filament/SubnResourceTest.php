<?php

namespace Tests\Feature\Filament;

use App\Filament\App\Resources\SubnResource;
use App\Models\Subn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubnResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Subn::class, SubnResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(SubnResource::getNavigationLabel());
    }

    public function test_subn_can_be_created_in_database(): void
    {
        $subn = Subn::factory()->create();

        $this->assertDatabaseHas('subns', ['id' => $subn->id]);
    }
}
