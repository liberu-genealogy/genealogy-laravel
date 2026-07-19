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
 * What makes this survivable today is a second defect: the tenant in the URL
 * does not yet control which records are shown, so opening another team's URL
 * shows you your own data rather than theirs. That is why the scoping ticket is
 * blocked on this one — fixing scoping first would turn a confusing display
 * into a genuine cross-tenant leak.
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

        $response = $this->actingAs($outsider)->get('/app/'.$owner->currentTeam->id);

        $this->assertContains(
            $response->getStatusCode(),
            [403, 404],
            'An outsider was served another team\'s panel URL.',
        );
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

    public function test_a_deleted_or_unknown_team_is_refused(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->assertFalse($user->canAccessTenant(new Team));
    }
}
