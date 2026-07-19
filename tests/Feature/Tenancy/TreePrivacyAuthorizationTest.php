<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Pages\TreePrivacy;
use App\Models\Tree;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Publishing a family tree is the most consequential thing this application
 * does — it puts records of living relatives on the public internet — and it
 * was the action with the least protection in front of it.
 *
 * The page carried no access check and the toggle no permission check, so any
 * member of a team could publish or unpublish all of its trees. A viewer,
 * invited to look at one family's research and nothing more, could make the
 * whole of it public.
 *
 * Custom pages are not resources, so the collaboration tiers AppResource
 * enforces do not reach them on their own; the check has to be stated. Other
 * pages in this directory still lack one — see the follow-up ticket — but this
 * one is not left waiting for it.
 */
class TreePrivacyAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_viewer_cannot_publish_a_tree(): void
    {
        [$tree] = $this->teamWithTree('viewer');

        $this->attemptToggle($tree->id);

        $this->assertFalse(
            (bool) $tree->fresh()->is_public,
            'A read-only member published the team\'s family tree.',
        );

        $this->assertFalse(TreePrivacy::canAccess(), 'A viewer could reach the privacy page.');
    }

    public function test_a_contributor_cannot_publish_a_tree(): void
    {
        [$tree] = $this->teamWithTree('contributor');

        $this->attemptToggle($tree->id);

        $this->assertFalse(
            (bool) $tree->fresh()->is_public,
            'Publishing is a deletion-grade decision and must not sit below the delete tier.',
        );
    }

    public function test_an_editor_can_publish_and_unpublish(): void
    {
        [$tree] = $this->teamWithTree('editor');

        Livewire::test(TreePrivacy::class)->call('toggle', $tree->id);
        $this->assertTrue((bool) $tree->fresh()->is_public, 'An editor could not publish.');

        Livewire::test(TreePrivacy::class)->call('toggle', $tree->id);
        $this->assertFalse((bool) $tree->fresh()->is_public, 'An editor could not make it private again.');
    }

    public function test_the_owner_can_publish(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $this->actingAs($owner);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        $tree = Tree::factory()->create(['team_id' => $owner->current_team_id, 'is_public' => false]);

        Livewire::test(TreePrivacy::class)->call('toggle', $tree->id);

        $this->assertTrue((bool) $tree->fresh()->is_public);
    }

    /**
     * A page the current user may not reach does not mount, so Livewire raises
     * on the snapshot rather than returning a response to assert against. The
     * assertion that matters is on the tree, not the transport: whatever shape
     * the refusal takes, the tree must not have been published.
     */
    private function attemptToggle(int $treeId): void
    {
        try {
            Livewire::test(TreePrivacy::class)->call('toggle', $treeId);
        } catch (\Throwable) {
            // Refused.
        }
    }

    /**
     * @return array{0: Tree, 1: User}
     */
    private function teamWithTree(string $tier): array
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => $tier]);

        $tree = Tree::factory()->create(['team_id' => $owner->current_team_id, 'is_public' => false]);

        $member = $member->fresh();
        $this->actingAs($member);
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        return [$tree, $member];
    }
}
