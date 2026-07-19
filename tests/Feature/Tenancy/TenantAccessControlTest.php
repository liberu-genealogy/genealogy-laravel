<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tenant authorisation returned true unconditionally, with the real condition
 * commented out on the same line. Any authenticated user could open any team's
 * URL by typing it. Nothing in the interface led there — the team switcher lists
 * only teams the user owns — so it was reachable by editing the address bar.
 *
 * Restoring the commented-out condition verbatim would have swapped one bug for
 * another: it tested ownership, so every member of a team they do not own would
 * have lost access to a team they legitimately belong to. Membership is the
 * right test, and the switcher needs the same widening or the fix is invisible
 * to exactly the users it should serve.
 *
 * A note on what this did NOT mask, because an earlier version of this comment
 * had it wrong. It claimed the URL did not control which records were shown, so
 * opening another team's URL showed you your own data. That is false for anyone
 * who legitimately belongs to the team: the SwitchTeam listener fires on
 * TenantSet and persists current_team_id to the tenant in the URL, and the
 * tenant scope reads that column — so data already follows the URL for them.
 * The old behaviour was an access hole, plainly, not a half-open one.
 */
class TenantAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_owner_can_access_their_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->assertTrue($user->canAccessTenant($user->currentTeam));
    }

    /**
     * The case the commented-out ownership check would have broken.
     */
    public function test_a_member_can_access_a_team_they_do_not_own(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($member, ['role' => 'editor']);

        $this->assertTrue($member->fresh()->canAccessTenant($owner->currentTeam));
    }

    public function test_an_outsider_cannot_access_someone_elses_team(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $outsider = User::factory()->withPersonalTeam()->create();

        $this->assertFalse($outsider->canAccessTenant($owner->currentTeam));
    }

    public function test_the_switcher_lists_teams_the_user_belongs_to_as_well_as_owns(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($member, ['role' => 'editor']);

        $tenants = $member->fresh()->getTenants(Filament::getPanel('app'));
        $ids = collect($tenants)->pluck('id');

        $this->assertTrue($ids->contains($member->current_team_id), 'Own team missing from the switcher.');
        $this->assertTrue($ids->contains($owner->current_team_id), 'A team the user belongs to was not listed.');
    }

    public function test_the_switcher_does_not_list_teams_the_user_cannot_reach(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $stranger = User::factory()->withPersonalTeam()->create();

        $tenants = $user->getTenants(Filament::getPanel('app'));
        $ids = collect($tenants)->pluck('id');

        $this->assertFalse($ids->contains($stranger->current_team_id));
    }

    /**
     * Every team the switcher offers must also pass the access check, or the
     * interface leads somewhere the authorisation refuses.
     */
    public function test_every_listed_team_is_one_the_user_may_access(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => 'editor']);

        $member = $member->fresh();

        foreach ($member->getTenants(Filament::getPanel('app')) as $team) {
            $this->assertTrue(
                $member->canAccessTenant($team),
                "Team {$team->id} is listed in the switcher but access is refused.",
            );
        }
    }

    /**
     * The predicate tests above prove the rule; this proves the route enforces
     * it. The ticket's claim is about URLs — "any authenticated user can open
     * any team's URL" — and a unit test on the method does not demonstrate that
     * the panel actually refuses the request.
     */
    public function test_opening_another_teams_url_is_refused(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $outsider = User::factory()->withPersonalTeam()->create();

        // 404 exactly, not "403 or 404": a loose assertion here would also pass
        // if the panel route were removed altogether, which is a false green on
        // the one test standing between a user and someone else's tenant.
        // Filament's IdentifyTenant aborts 404 rather than 403, which also means
        // an unauthorised team and a nonexistent one are indistinguishable — no
        // existence disclosure.
        $this->actingAs($outsider)
            ->get('/app/'.$owner->currentTeam->id)
            ->assertNotFound();
    }

    public function test_opening_your_own_teams_url_is_allowed(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)
            ->get('/app/'.$user->current_team_id)
            ->assertSuccessful();
    }

    public function test_a_member_may_open_the_url_of_a_team_they_do_not_own(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => 'editor']);

        $this->actingAs($member->fresh())
            ->get('/app/'.$owner->current_team_id)
            ->assertSuccessful();
    }

    /**
     * Guards a dead end this change introduced. current_team_id can outlive
     * membership — removeUser() and a bare detach() both leave it set — and the
     * default tenant used to be read straight off it. Once access stopped being
     * unconditional, that meant login redirecting to a tenant the access check
     * then refused: a 404 with no way out.
     */
    public function test_a_user_removed_from_their_current_team_still_lands_somewhere_reachable(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $ownTeam = $member->currentTeam;

        $owner->currentTeam->users()->attach($member, ['role' => 'editor']);
        $member->forceFill(['current_team_id' => $owner->current_team_id])->save();

        // Removed from that team, but the column still points at it.
        $owner->currentTeam->users()->detach($member);

        $default = $member->fresh()->getDefaultTenant(Filament::getPanel('app'));

        $this->assertNotNull($default, 'A removed member was left with no default tenant.');
        $this->assertSame($ownTeam->id, $default->id);
        $this->assertTrue($member->fresh()->canAccessTenant($default));
    }

    /**
     * A member who owns nothing — an invited account — used to get null here,
     * because the fallback only considered owned teams. LoginResponse reads this
     * directly, so they were sent to create a team despite belonging to one.
     */
    public function test_a_member_who_owns_no_team_still_has_a_default_tenant(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->create();

        $owner->currentTeam->users()->attach($member, ['role' => 'editor']);

        $default = $member->fresh()->getDefaultTenant(Filament::getPanel('app'));

        $this->assertNotNull($default, 'An invited member was left with no team to land on.');
        $this->assertSame($owner->current_team_id, $default->id);
    }

    public function test_a_deleted_or_unknown_team_is_refused(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->assertFalse($user->canAccessTenant(new Team));
    }
}
