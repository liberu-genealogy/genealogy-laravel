<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Dna;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * DnaController::store() only validated variable_name, but the dnas table
 * requires name, file_name and user_id (all NOT NULL). A well-formed request
 * therefore 500'd on the INSERT. It also validated a gedcom_id that no column
 * or fillable accepts. Guards the store path against the real schema.
 */
class DnaApiTest extends TestCase
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

    public function test_store_creates_dna_for_authenticated_user(): void
    {
        $response = $this->postJson('/api/dna', [
            'name' => 'Kit A',
            'file_name' => 'kit-a.txt',
            'variable_name' => 'var_abc',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['variable_name' => 'var_abc']);

        $this->assertDatabaseHas('dnas', [
            'variable_name' => 'var_abc',
            'name' => 'Kit A',
            'file_name' => 'kit-a.txt',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_store_requires_the_columns_the_table_needs(): void
    {
        $this->postJson('/api/dna', ['variable_name' => 'var_xyz'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'file_name']);
    }

    public function test_store_rejects_duplicate_variable_name(): void
    {
        Dna::factory()->create(['variable_name' => 'var_dup']);

        $this->postJson('/api/dna', [
            'name' => 'Kit B',
            'file_name' => 'kit-b.txt',
            'variable_name' => 'var_dup',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['variable_name']);
    }
}
