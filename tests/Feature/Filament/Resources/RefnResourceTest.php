<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\RefnResource;
use App\Models\Refn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefnResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Refn::class, RefnResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(RefnResource::getNavigationLabel());
    }

    public function test_refn_can_be_created_in_database(): void
    {
        $refn = Refn::factory()->create();

        $this->assertDatabaseHas('refn', ['id' => $refn->id]);
    }
}
