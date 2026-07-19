<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Pages\TeamMembers;
use App\Filament\App\Resources\PersonResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The surface an owner uses to set a collaborator's tier.
 *
 * Enforcing the tiers made them matter; until this page there was nowhere to
 * set one. Jetstream's team management views are not routed in this
 * application, and the tenant profile page carries no member management, so a
 * tier could only be changed in the database.
 *
 * The tiers themselves are Jetstream's, defined in the service provider and
 * stored on the membership. This page deliberately does not create roles: SPEC
 * §10 names a fixed set of four, and letting a team define its own would mean
 * inventing a vocabulary the enforcement side does not understand.
 */
class TeamMembersPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_owner_can_change_a_members_tier(): void
    {
        [$owner, $member] = $this->teamWithMember('viewer');

        Livewire::test(TeamMembers::class)
            ->callAction('setTier', arguments: ['user' => $member->id], data: ['role' => 'editor'])
            ->assertHasNoActionErrors();

        $this->assertSame(
            'editor',
            $owner->currentTeam->fresh()->users->find($member->id)->membership->role,
        );
    }

    /**
     * The point of the whole exercise: the tier is not decoration, so changing
     * it must change what the member may do.
     */
    public function test_changing_the_tier_changes_what_the_member_may_do(): void
    {
        [$owner, $member] = $this->teamWithMember('viewer');
        $team = $owner->currentTeam;

        $this->actingAs($member->fresh());
        Filament::setTenant($team->fresh(), isQuiet: true);
        $this->assertFalse(
            PersonResource::canCreate(),
            'Fixture is degenerate: the viewer could already create.',
        );

        $this->actingAs($owner);
        Filament::setTenant($team, isQuiet: true);
        Livewire::test(TeamMembers::class)
            ->callAction('setTier', arguments: ['user' => $member->id], data: ['role' => 'editor']);

        $this->actingAs($member->fresh());
        Filament::setTenant($team->fresh(), isQuiet: true);

        $this->assertTrue(
            PersonResource::canCreate(),
            'The member\'s rights did not follow the tier they were given.',
        );
    }

    /**
     * A tier is held per team. Promoting someone in one team must not promote
     * them in another they happen to belong to.
     */
    public function test_a_tier_change_does_not_leak_into_another_team(): void
    {
        [$owner, $member] = $this->teamWithMember('viewer');

        $otherOwner = User::factory()->withPersonalTeam()->create();
        $otherTeam = $otherOwner->currentTeam;
        $otherTeam->users()->attach($member, ['role' => 'viewer']);

        Livewire::test(TeamMembers::class)
            ->callAction('setTier', arguments: ['user' => $member->id], data: ['role' => 'editor']);

        $this->assertSame(
            'viewer',
            $otherTeam->fresh()->users->find($member->id)->membership->role,
            'Promoting a member in one team promoted them in another.',
        );
    }

    public function test_a_member_who_is_not_the_owner_cannot_reach_the_page(): void
    {
        [, $member] = $this->teamWithMember('editor');

        $this->actingAs($member->fresh());

        $this->assertFalse(TeamMembers::canAccess(), 'A non-owner could reach team member management.');
    }

    /**
     * The predicate above states the rule; this proves the page enforces it,
     * since an action reachable by other means would make the predicate
     * decorative.
     */
    public function test_a_non_owner_cannot_change_a_tier_through_the_action(): void
    {
        [$owner, $member] = $this->teamWithMember('editor');
        $other = User::factory()->create();
        $owner->currentTeam->users()->attach($other, ['role' => 'viewer']);

        $this->actingAs($member->fresh());
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        // The page refuses to mount for a non-owner, so the action cannot be
        // reached through it at all. Asserting on the action's own response
        // would be asserting against a component that never came up.
        Livewire::test(TeamMembers::class)->assertForbidden();

        $this->assertSame(
            'viewer',
            $owner->currentTeam->fresh()->users->find($other->id)->membership->role,
            'A non-owner promoted another member.',
        );
    }

    /**
     * Ownership can be lost while the page sits open, and the change must stop
     * working at that moment rather than at the next full page load.
     *
     * This holds because Filament re-runs canAccess() on every Livewire
     * hydration, not only on mount. The page relies on that entirely — it
     * carries no per-action authorisation — so this test is what pins the
     * behaviour being relied on.
     *
     * It exists because the reverse was assumed first. The page did have a Gate
     * check inside the action, and deleting it changed nothing: every test here
     * still passed, because canAccess had already refused the request before
     * the action ran. The check could not fire, so it went, and this took its
     * place.
     */
    public function test_ownership_lost_after_the_page_is_open_stops_further_changes(): void
    {
        [$owner, $member] = $this->teamWithMember('viewer');
        $team = $owner->currentTeam;

        $page = Livewire::test(TeamMembers::class);

        // The team is handed to someone else while the page sits open.
        $usurper = User::factory()->create();
        $team->forceFill(['user_id' => $usurper->id])->save();
        Filament::setTenant($team->fresh(), isQuiet: true);

        // Asserted on the outcome rather than the response: a component the
        // former owner may no longer access does not re-hydrate, and Livewire's
        // test harness surfaces that as a null instance rather than a 403. What
        // matters is that the change does not happen.
        try {
            $page->callAction('setTier', arguments: ['user' => $member->id], data: ['role' => 'admin']);
        } catch (\Throwable) {
            // Refused, in whichever form.
        }

        $this->assertSame(
            'viewer',
            $team->fresh()->users->find($member->id)->membership->role,
            'A former owner changed a tier through a page opened while they still owned the team.',
        );
    }

    public function test_the_owner_is_listed_and_cannot_be_demoted(): void
    {
        [$owner] = $this->teamWithMember('viewer');

        Livewire::test(TeamMembers::class)
            ->callAction('setTier', arguments: ['user' => $owner->id], data: ['role' => 'viewer'])
            ->assertForbidden();
    }

    /**
     * @return array{0: User, 1: User}
     */
    private function teamWithMember(string $tier): array
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($member, ['role' => $tier]);

        $owner = $owner->fresh();
        $this->actingAs($owner);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        return [$owner, $member];
    }
}
