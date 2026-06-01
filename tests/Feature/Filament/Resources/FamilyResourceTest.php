<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\FamilyResource;
use App\Models\Family;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_resource_has_correct_model(): void
    {
        $this->assertSame(Family::class, FamilyResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(FamilyResource::getNavigationLabel());
    }

    public function test_resource_has_pages_defined(): void
    {
        $pages = FamilyResource::getPages();

        $this->assertArrayHasKey('index', $pages);
    }

    public function test_family_can_be_created_in_database(): void
    {
        $family = Family::factory()->create();

        $this->assertDatabaseHas('families', ['id' => $family->id]);
    }
}
