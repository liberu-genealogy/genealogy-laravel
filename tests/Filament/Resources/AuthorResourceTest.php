<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Author::class, AuthorResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = AuthorResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $authorData = [
            'name'        => 'John Doe',
            'description' => 'An author',
            'is_active'   => true,
        ];

        // Create
        $author = Author::create($authorData);
        $this->assertDatabaseHas('authors', ['name' => 'John Doe', 'description' => 'An author']);

        // Read
        $retrievedAuthor = Author::find($author->id);
        $this->assertNotNull($retrievedAuthor);

        // Update
        $author->update(['name' => 'Jane Doe']);
        $this->assertDatabaseHas('authors', ['name' => 'Jane Doe']);

        // Delete
        $author->delete();
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
