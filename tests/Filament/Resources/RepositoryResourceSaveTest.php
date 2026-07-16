<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Models\Repository;
use App\Models\Type;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositoryResourceSaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_saves_repository_with_null_type_id(): void
    {
        $repository = Repository::factory()->create(['type_id' => null]);

        $this->assertNull($repository->fresh()->type_id);
        $this->assertDatabaseHas('repositories', ['id' => $repository->id, 'type_id' => null]);
    }

    public function test_saves_repository_with_seeded_type_id(): void
    {
        $this->seed(TypeSeeder::class);
        $type = Type::first();
        $this->assertNotNull($type, 'TypeSeeder should create at least one type.');

        $repository = Repository::factory()->create(['type_id' => $type->id]);

        $this->assertSame($type->id, $repository->fresh()->type_id);
        $this->assertDatabaseHas('repositories', ['id' => $repository->id, 'type_id' => $type->id]);
    }
}
