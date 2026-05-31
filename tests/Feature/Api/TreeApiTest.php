<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TreeApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
        $team = $this->user->ownedTeams()->first();
        if ($team) {
            $this->user->forceFill(['current_team_id' => $team->id])->save();
            $this->user->setRelation('currentTeam', $team);
        }
        Sanctum::actingAs($this->user);
    }

    public function test_index_returns_trees(): void
    {
        Tree::factory()->count(2)->create();

        $this->getJson('/api/trees')
             ->assertOk()
             ->assertJsonStructure(['data']);
    }

    public function test_store_creates_tree(): void
    {
        $response = $this->postJson('/api/trees', [
            'name'        => 'My Family Tree',
            'description' => 'A test tree',
        ]);

        $response->assertCreated()
                 ->assertJsonFragment(['name' => 'My Family Tree']);

        $this->assertDatabaseHas('trees', ['name' => 'My Family Tree']);
    }

    public function test_store_requires_name(): void
    {
        $this->postJson('/api/trees', [])
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['name']);
    }

    public function test_show_returns_tree(): void
    {
        $tree = Tree::factory()->create();

        $this->getJson("/api/trees/{$tree->id}")
             ->assertOk()
             ->assertJsonFragment(['id' => $tree->id]);
    }

    public function test_update_modifies_tree(): void
    {
        $tree = Tree::factory()->create(['name' => 'Original']);

        $this->putJson("/api/trees/{$tree->id}", ['name' => 'Updated'])
             ->assertOk()
             ->assertJsonFragment(['name' => 'Updated']);
    }

    public function test_destroy_deletes_tree(): void
    {
        $tree = Tree::factory()->create();

        $this->deleteJson("/api/trees/{$tree->id}")->assertNoContent();

        $this->assertDatabaseMissing('trees', ['id' => $tree->id]);
    }

    public function test_statistics_returns_counts(): void
    {
        $tree = Tree::factory()->create();

        $this->getJson("/api/trees/{$tree->id}/statistics")
             ->assertOk()
             ->assertJsonStructure(['people_count', 'families_count']);
    }
}
