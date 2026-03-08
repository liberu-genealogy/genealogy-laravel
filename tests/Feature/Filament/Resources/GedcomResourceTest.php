<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\GedcomResource;
use App\Jobs\ExportGedCom;
use App\Models\Gedcom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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

    public function test_can_create_returns_true_for_authenticated_user(): void
    {
        Auth::login($this->user);

        $this->assertTrue(GedcomResource::canCreate());
    }

    public function test_can_create_returns_false_when_unauthenticated(): void
    {
        Auth::logout();

        $this->assertFalse(GedcomResource::canCreate());
    }

    public function test_export_gedcom_dispatches_job_with_authenticated_user(): void
    {
        Auth::login($this->user);

        GedcomResource::exportGedcom();

        Queue::assertPushed(ExportGedCom::class, fn ($job): bool => $job->user->id === $this->user->id);
    }

    public function test_export_gedcom_does_not_dispatch_without_authenticated_user(): void
    {
        Auth::logout();

        GedcomResource::exportGedcom();

        Queue::assertNotPushed(ExportGedCom::class);
    }
}
