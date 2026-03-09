<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\ImportJobResource;
use App\Filament\App\Resources\ImportJobResource\Pages\ListImportJobs;
use App\Models\ImportJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ImportJobResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(ImportJob::class, ImportJobResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(ImportJobResource::getNavigationLabel());
        $this->assertNotEmpty(ImportJobResource::getNavigationIcon());
    }

    public function test_resource_has_pages_defined(): void
    {
        $pages = ImportJobResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertCount(1, $pages, 'ImportJobResource should only have an index page (read-only)');
    }

    public function test_can_create_returns_false(): void
    {
        Auth::login($this->user);

        $this->assertFalse(ImportJobResource::canCreate());
    }

    public function test_can_view_any_returns_true_for_authenticated_user(): void
    {
        Auth::login($this->user);

        $this->assertTrue(ImportJobResource::canViewAny());
    }

    public function test_can_view_any_returns_false_when_unauthenticated(): void
    {
        Auth::logout();

        $this->assertFalse(ImportJobResource::canViewAny());
    }

    public function test_eloquent_query_scopes_to_current_user(): void
    {
        Auth::login($this->user);

        $otherUser = User::factory()->create();

        ImportJob::factory()->create(['user_id' => $this->user->id, 'status' => 'complete']);
        ImportJob::factory()->create(['user_id' => $otherUser->id, 'status' => 'complete']);

        $query = ImportJobResource::getEloquentQuery();
        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals($this->user->id, $results->first()->user_id);
    }

    public function test_navigation_group_is_data_management(): void
    {
        $this->assertStringContainsString('Data Management', ImportJobResource::getNavigationGroup());
    }

    public function test_list_page_class_resolves_correctly(): void
    {
        $pages = ImportJobResource::getPages();
        $this->assertStringContainsString('ListImportJobs', $pages['index']->getPage());
    }
}
