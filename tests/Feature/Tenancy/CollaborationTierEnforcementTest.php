<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Resources\PersonResource;
use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The collaboration tiers govern what a member may do to a team's records.
 *
 * SPEC §10 names four of them — viewer, contributor, editor, admin — and they
 * are defined, with explicit permissions, in the Jetstream service provider.
 * Members are given one when they are added to a team, and it is stored against
 * their membership.
 *
 * Nothing read it. Not one call to hasTeamPermission or hasTeamRole existed in
 * the application, and the base resource that all 44 app-panel resources extend
 * answered every authorisation question with auth()->check(). So a viewer
 * invited to look at a family's research could delete every person in it, and
 * the tier they were given was decoration.
 *
 * The tiers are strict supersets of one another, so these tests walk the
 * boundaries rather than asserting each tier in isolation: the interesting
 * question is always where one tier stops and the next begins.
 */
class CollaborationTierEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_viewer_may_read_but_not_change_anything(): void
    {
        $this->actingAsMemberWithTier('viewer');

        $this->assertTrue(PersonResource::canViewAny(), 'A viewer could not read.');
        $this->assertFalse(PersonResource::canCreate(), 'A viewer could create records.');
        $this->assertFalse(PersonResource::canEdit($this->person()), 'A viewer could edit records.');
        $this->assertFalse(PersonResource::canDelete($this->person()), 'A viewer could delete records.');
    }

    public function test_a_contributor_may_create_and_update_but_not_delete(): void
    {
        $this->actingAsMemberWithTier('contributor');

        $this->assertTrue(PersonResource::canCreate());
        $this->assertTrue(PersonResource::canEdit($this->person()));
        $this->assertFalse(
            PersonResource::canDelete($this->person()),
            'A contributor could delete records; the tier exists precisely to withhold that.',
        );
    }

    public function test_an_editor_may_delete(): void
    {
        $this->actingAsMemberWithTier('editor');

        $this->assertTrue(PersonResource::canDelete($this->person()));
    }

    /**
     * The owner is not a row in the membership table and holds no tier, so a
     * naive lookup returns nothing for them. They must not lose access to their
     * own team's records.
     */
    public function test_the_owner_may_do_everything_without_holding_a_tier(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $this->actingAs($owner);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        $this->assertTrue(PersonResource::canViewAny());
        $this->assertTrue(PersonResource::canCreate());
        $this->assertTrue(PersonResource::canDelete($this->person()));
    }

    /**
     * Guards the failure mode that would make every test above pass while the
     * application was wide open: a check that resolves to "allowed" whenever it
     * cannot determine a tier.
     */
    public function test_someone_with_no_membership_at_all_is_refused(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $outsider = User::factory()->withPersonalTeam()->create();

        $this->actingAs($outsider);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        $this->assertFalse(PersonResource::canCreate(), 'A non-member could create records in another team.');
        $this->assertFalse(PersonResource::canViewAny(), 'A non-member could read another team\'s records.');
    }

    /**
     * Console commands and queued jobs have no tenant. Authorisation must not
     * silently allow everything there.
     */
    public function test_without_a_tenant_nothing_is_authorised(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant(null, isQuiet: true);

        $this->assertFalse(PersonResource::canCreate());
    }

    /**
     * Every hook, not just the ones a first pass thinks of.
     *
     * An adversarial review gutted canView, canDeleteAny, canForceDelete,
     * canForceDeleteAny, canRestore and canRestoreAny to return true and the
     * suite stayed green — six of the ten authorisation hooks were asserted by
     * nothing. Enumerating them here means adding a hook without a test is the
     * thing that fails.
     */
    public function test_every_mutating_hook_refuses_a_viewer(): void
    {
        $this->actingAsMemberWithTier('viewer');
        $person = $this->person();

        $this->assertTrue(PersonResource::canView($person), 'A viewer must still be able to read.');

        $refused = [
            'canCreate' => PersonResource::canCreate(),
            'canEdit' => PersonResource::canEdit($person),
            'canDelete' => PersonResource::canDelete($person),
            'canDeleteAny' => PersonResource::canDeleteAny(),
            'canForceDelete' => PersonResource::canForceDelete($person),
            'canForceDeleteAny' => PersonResource::canForceDeleteAny(),
            'canRestore' => PersonResource::canRestore($person),
            'canRestoreAny' => PersonResource::canRestoreAny(),
        ];

        $this->assertSame(
            array_fill_keys(array_keys($refused), false),
            $refused,
            'A viewer was permitted a mutating action.',
        );
    }

    /**
     * The authorisation response is what Filament consults for actions that do
     * not have their own can* hook, and it was untested: it could be replaced
     * with an unconditional allow without a single failure.
     *
     * The action names below are the ones Filament passes for record mutation
     * beyond plain create/update/delete. They were previously unlisted, so they
     * fell through a `default => 'read'` arm and a viewer held every one of
     * them — replicate duplicates a record, detach and dissociate tear apart
     * relationships. The default now denies, so a name nobody anticipated is
     * refused rather than quietly treated as reading.
     */
    public function test_unanticipated_mutating_actions_are_refused_for_a_viewer(): void
    {
        $this->actingAsMemberWithTier('viewer');

        $mutating = ['replicate', 'reorder', 'attach', 'detach', 'detachAny', 'associate', 'dissociate', 'dissociateAny', 'somethingNobodyHasWrittenYet'];

        $allowed = array_values(array_filter(
            $mutating,
            fn (string $action): bool => PersonResource::getAuthorizationResponse($action)->allowed(),
        ));

        $this->assertSame([], $allowed, 'A viewer was allowed actions that mutate records.');
    }

    public function test_the_authorization_response_still_permits_what_a_tier_carries(): void
    {
        $this->actingAsMemberWithTier('editor');

        $this->assertTrue(PersonResource::getAuthorizationResponse('view')->allowed());
        $this->assertTrue(PersonResource::getAuthorizationResponse('create')->allowed());
        $this->assertTrue(PersonResource::getAuthorizationResponse('delete')->allowed());
    }

    /**
     * A membership can carry no tier at all — the column is nullable, and rows
     * predating enforcement have nothing in it.
     *
     * Such a member is refused everything, including reading, and that is the
     * correct runtime answer: a tier nobody chose is not evidence of a grant.
     * But it is a lockout with no way back, since only the owner can set a
     * tier, so leaving existing rows to hit it would be silently taking access
     * away from real collaborators. They are backfilled by migration instead —
     * see BackfillCollaborationTierTest — and this pins the behaviour that
     * makes the backfill necessary rather than optional.
     */
    public function test_a_membership_with_no_tier_is_refused(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($member, ['role' => null]);

        $this->actingAs($member->fresh());
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        $this->assertFalse(PersonResource::canViewAny());
        $this->assertFalse(PersonResource::canCreate());
    }

    private function actingAsMemberWithTier(string $tier): User
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($member, ['role' => $tier]);

        $member = $member->fresh();
        $this->actingAs($member);
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        return $member;
    }

    private function person(): Person
    {
        return Person::factory()->make(['team_id' => Filament::getTenant()?->getKey()]);
    }
}
