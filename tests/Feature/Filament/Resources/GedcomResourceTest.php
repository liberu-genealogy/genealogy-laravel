<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\GedcomResource;
use App\Jobs\ExportGedCom;
use App\Models\Gedcom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->user = User::factory()->create();
    }

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Gedcom::class, GedcomResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(GedcomResource::getNavigationLabel());
        $this->assertNotEmpty(GedcomResource::getNavigationIcon());
    }

    public function test_resource_has_pages_defined(): void
    {
        $pages = GedcomResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
    }
}
