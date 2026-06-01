<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\RepositoryResource;
use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositoryResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Repository::class, RepositoryResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = RepositoryResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $repository = Repository::factory()->create([
            'name' => 'Test Repository',
        ]);

        $this->assertDatabaseHas('repositories', ['name' => 'Test Repository']);

        $retrieved = Repository::find($repository->id);
        $this->assertNotNull($retrieved);

        $repository->update(['name' => 'Updated Repository']);
        $this->assertDatabaseHas('repositories', ['name' => 'Updated Repository']);

        $repository->delete();
        $this->assertDatabaseMissing('repositories', ['id' => $repository->id]);
    }
}
