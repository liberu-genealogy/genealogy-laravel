<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\DnaMatchingResource;
use App\Models\DnaMatching;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DnaMatchingResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(DnaMatching::class, DnaMatchingResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = DnaMatchingResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $user = User::factory()->create();
        $matchUser = User::factory()->create();

        $dnaMatching = DnaMatching::factory()->create([
            'user_id' => $user->id,
            'match_id' => $matchUser->id,
            'match_name' => 'Test Match',
        ]);

        $this->assertDatabaseHas('dna_matchings', ['match_name' => 'Test Match']);

        $retrieved = DnaMatching::find($dnaMatching->id);
        $this->assertNotNull($retrieved);

        $dnaMatching->update(['match_name' => 'Updated Match']);
        $this->assertDatabaseHas('dna_matchings', ['match_name' => 'Updated Match']);

        $dnaMatching->delete();
        $this->assertDatabaseMissing('dna_matchings', ['id' => $dnaMatching->id]);
    }
}
