<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\SourceResource;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceResourceTest extends TestCase
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
        $this->assertSame(Source::class, SourceResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(SourceResource::getNavigationLabel());
    }

    public function test_resource_has_pages_defined(): void
    {
        $pages = SourceResource::getPages();

        $this->assertArrayHasKey('index', $pages);
    }

    public function test_source_model_class_exists(): void
    {
        $this->assertTrue(class_exists(Source::class));
    }

    public function test_source_resource_model_is_source(): void
    {
        $this->assertSame(Source::class, SourceResource::getModel());
    }
}
