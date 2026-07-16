<?php

declare(strict_types=1);

namespace Tests\Feature\Trees;

use App\Models\Tree;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Per-tree privacy (SCOPE §3/§20): trees are private by default and only
 * surface publicly once the owner opts in. Tree is tenant-scoped via
 * BelongsToTenant, so a tenant must be set for the global scope to behave.
 */
class TreePrivacyTest extends TestCase
{
    use RefreshDatabase;

    private function actAsTenant(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_a_new_tree_is_private_by_default(): void
    {
        $this->actAsTenant();

        $tree = Tree::factory()->create();

        $this->assertFalse($tree->is_public, 'A newly created tree must default to private.');
        $this->assertFalse($tree->fresh()->is_public, 'The private default must persist to the database.');
    }

    public function test_public_scope_returns_only_public_trees(): void
    {
        $this->actAsTenant();

        $private = Tree::factory()->create(); // is_public defaults to false
        $public = Tree::factory()->create(['is_public' => true]);

        $ids = Tree::public()->pluck('id');

        $this->assertTrue($ids->contains($public->id), 'Public tree must be returned by public() scope.');
        $this->assertFalse($ids->contains($private->id), 'Private tree must NOT be returned by public() scope.');
    }

    public function test_toggling_visibility_persists(): void
    {
        $this->actAsTenant();

        $tree = Tree::factory()->create();

        $tree->is_public = true;
        $tree->save();

        $this->assertTrue($tree->fresh()->is_public, 'Flipping a tree to public must persist.');
    }
}
