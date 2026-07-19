<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Pages\EditTeam;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Editing a team's profile is owner-only, and this pins it.
 *
 * The page carries no authorization of its own — and does not need to. It
 * extends Filament's EditTenantProfile, whose canView() runs authorize('update')
 * against the tenant, which routes through TeamPolicy::update (ownership). That
 * check is enforced on mount and re-run on every Livewire hydration, so a
 * non-owner cannot reach the page or its save path.
 *
 * There is no code to add here, which is exactly why this test exists. The page
 * is a trap in waiting: its form fields are all commented out, so nothing is
 * exploitable today, and the next person to add a field has no reason to suspect
 * authorization is anywhere but where every other panel page keeps it. This
 * fails the moment that inherited gate stops covering the page — a Filament
 * upgrade that renames the hook, an override that forgets it, a policy change.
 *
 * Ownership, not an admin tier, is deliberate. Editing the team profile is the
 * team's identity and, per the commented-out form, its subscription — an
 * owner/billing concern, like team membership (TeamMembers is likewise
 * owner-only), not the day-to-day research a collaborator does (TreePrivacy,
 * which is tier-gated). An admin-tier genealogy collaborator should not be able
 * to rename the team or touch its billing. The admin-tier-refused case below is
 * what encodes that decision.
 */
class EditTeamAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_owner_may_edit_the_team_profile(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $this->actingAs($owner);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        $this->assertTrue(EditTeam::canView($owner->currentTeam));
    }

    /**
     * Even the highest collaboration tier is not ownership. A member granted
     * admin in the team still cannot edit its profile — the point of gating on
     * ownership rather than a tier.
     */
    public function test_an_admin_tier_member_may_not_edit_the_team_profile(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $member = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($member, ['role' => 'admin']);

        $member = $member->fresh();
        $this->actingAs($member);
        Filament::setTenant($owner->currentTeam->fresh(), isQuiet: true);

        $this->assertFalse(
            EditTeam::canView($owner->currentTeam),
            'An admin-tier member could edit the team profile; it is meant to be owner-only.',
        );
    }

    public function test_an_outsider_may_not_edit_a_team_profile(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $outsider = User::factory()->withPersonalTeam()->create();

        $this->actingAs($outsider);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        $this->assertFalse(EditTeam::canView($owner->currentTeam));
    }
}
