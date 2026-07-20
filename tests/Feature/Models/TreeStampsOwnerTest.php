<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Tree;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * trees.user_id is NOT NULL with no database default, so a create that omits it
 * throws "Field 'user_id' doesn't have a default value" (issue #1548, during a
 * GEDCOM import). BelongsToTenant already stamps team_id for exactly this
 * reason; the owner is stamped the same way from the authenticated user.
 */
final class TreeStampsOwnerTest extends TestCase
{
    use RefreshDatabase;

    private function actAsTeamMember(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        return $user;
    }

    public function test_creating_a_tree_without_a_user_id_stamps_the_authenticated_owner(): void
    {
        $user = $this->actAsTeamMember();

        $tree = Tree::create(['name' => 'Royal92', 'description' => 'Imported tree']);

        $this->assertSame($user->id, $tree->fresh()->user_id);
    }

    public function test_an_explicit_user_id_is_left_untouched(): void
    {
        $actor = $this->actAsTeamMember();
        $owner = User::factory()->create();

        $tree = Tree::create(['name' => 'Owned', 'description' => 'x', 'user_id' => $owner->id]);

        $this->assertSame($owner->id, $tree->fresh()->user_id);
        $this->assertNotSame($actor->id, $tree->fresh()->user_id);
    }
}
