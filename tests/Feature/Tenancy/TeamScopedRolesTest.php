<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * A role granted in one team must not carry into another.
 *
 * Roles were global: permission.teams was false and the roles tables had no
 * team_id column, so granting someone administrator so they could manage one
 * family's research granted it in every team they belonged to — including
 * teams whose owner invited them as a read-only collaborator. There was no way
 * to express "administrator here, viewer there", because there was nowhere to
 * store the second role.
 *
 * The distinction these tests turn on is between a role's *definition* and its
 * *assignment*, and it is narrower than it first appears. A role with a null
 * team is visible from every team; an assignment always names one, because the
 * team key is part of the pivot's primary key and cannot be null. There is no
 * such thing as a grant that applies everywhere.
 *
 * So "global role" means only that the definition is team-less — which is worth
 * being exact about, because it is the property the admin panel gate depends on
 * and the reason that gate cannot simply call hasRole().
 */
class TeamScopedRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_role_granted_in_one_team_is_not_held_in_another(): void
    {
        [$user, $teamA, $teamB] = $this->userInTwoTeams();

        $this->inTeam($teamA, fn () => $user->assignRole($this->role('administrator')));

        $this->assertTrue(
            $this->inTeam($teamA, fn () => $user->fresh()->hasRole('administrator')),
            'The role was not held in the team it was granted in.',
        );

        $this->assertFalse(
            $this->inTeam($teamB, fn () => $user->fresh()->hasRole('administrator')),
            'A role granted in one team was held in another.',
        );
    }

    public function test_a_user_holds_different_roles_in_different_teams(): void
    {
        [$user, $teamA, $teamB] = $this->userInTwoTeams();

        $this->inTeam($teamA, fn () => $user->assignRole($this->role('administrator')));
        $this->inTeam($teamB, fn () => $user->assignRole($this->role('viewer')));

        $inA = $this->inTeam($teamA, fn () => $user->fresh()->getRoleNames()->all());
        $inB = $this->inTeam($teamB, fn () => $user->fresh()->getRoleNames()->all());

        $this->assertSame(['administrator'], $inA);
        $this->assertSame(['viewer'], $inB);
    }

    /**
     * The criterion ticket 02 could not meet: the role must be correct on the
     * first request after switching, not on the one after that. The middleware
     * that sets the permission team already reads the tenant from the URL; this
     * proves a role check actually honours what it wrote.
     */
    public function test_the_role_follows_the_tenant_in_the_url(): void
    {
        [$user, $teamA, $teamB] = $this->userInTwoTeams();

        $this->inTeam($teamA, fn () => $user->assignRole($this->role('administrator')));
        $this->inTeam($teamB, fn () => $user->assignRole($this->role('viewer')));

        // Arrive on team A, navigate to team B. Nothing has synced yet.
        $user->forceFill(['current_team_id' => $teamA])->save();

        $this->actingAs($user->fresh())->get('/app/'.$teamB)->assertSuccessful();

        $this->assertSame(
            $teamB,
            app(PermissionRegistrar::class)->getPermissionsTeamId(),
            'The permission team did not follow the URL.',
        );

        $this->assertSame(
            ['viewer'],
            $user->fresh()->getRoleNames()->all(),
            'The request resolved roles against the team the user arrived on.',
        );
    }

    /**
     * A role defined without a team is held in every team.
     *
     * The permission library does not do this on its own — it filters the
     * grant by the current team as well as the role, so a team-less role
     * applied only in whichever team its grant was written in. User::roles()
     * overrides that. The reasoning, and the admin panel breakage that forced
     * it, are recorded there and in AdminPanelAuthorizationTest.
     */
    public function test_a_team_less_role_is_held_in_every_team(): void
    {
        [$user, $teamA, $teamB] = $this->userInTwoTeams();

        $global = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->assertNull($global->team_id, 'A role created without a team context must be team-less.');

        $this->inTeam($teamA, fn () => $user->assignRole($global));

        $this->assertTrue($this->inTeam($teamA, fn () => $user->fresh()->hasRole('super_admin')));
        $this->assertTrue(
            $this->inTeam($teamB, fn () => $user->fresh()->hasRole('super_admin')),
            'A team-less role stopped applying outside the team its grant was written in.',
        );
    }

    /**
     * The boundary that makes the rule above safe. Team-less roles apply
     * everywhere, so it must not be possible to produce one from inside a team
     * — otherwise a member could mint global rights for themselves.
     */
    public function test_a_role_created_inside_a_team_is_never_team_less(): void
    {
        [, $teamA] = $this->userInTwoTeams();

        $scoped = $this->inTeam($teamA, fn () => Role::create(['name' => 'administrator', 'guard_name' => 'web']));

        $this->assertSame($teamA, $scoped->team_id, 'A role created inside a team escaped that team.');
    }

    /**
     * The admin panel has no tenancy, so its gate must not move with whichever
     * team the user last had open. Before this, a super admin kept or lost the
     * admin panel depending on which family tree they were working in, with
     * nothing failing to show for it.
     */
    public function test_the_admin_panel_gate_does_not_depend_on_the_current_team(): void
    {
        [$user, $teamA, $teamB] = $this->userInTwoTeams();

        // Created outside any team context, as the seeder does, so it is
        // team-less. Creating it inside one produces a team-scoped role that
        // merely shares the name — which the test below relies on.
        $global = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->assertNull($global->team_id, 'Fixture is degenerate: the role is not team-less.');

        $this->inTeam($teamA, fn () => $user->assignRole($global));

        $panel = Filament::getPanel('admin');

        $this->assertTrue($this->inTeam($teamA, fn () => $user->fresh()->canAccessPanel($panel)));
        $this->assertTrue(
            $this->inTeam($teamB, fn () => $user->fresh()->canAccessPanel($panel)),
            'A super admin lost the admin panel by switching team.',
        );
    }

    /**
     * The reason the gate tests the role definition rather than "holds it in
     * some team". A role created from inside a team carries that team, so a
     * member who could create one must not be able to name it super_admin and
     * take the admin panel with it.
     */
    public function test_a_role_minted_inside_a_team_does_not_open_the_admin_panel(): void
    {
        [$user, $teamA] = $this->userInTwoTeams();

        $this->inTeam($teamA, function () use ($user) {
            $forged = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
            $this->assertSame($user->current_team_id, $forged->team_id, 'Fixture is degenerate: the role was not team-scoped.');
            $user->assignRole($forged);
        });

        $this->assertFalse(
            $this->inTeam($teamA, fn () => $user->fresh()->canAccessPanel(Filament::getPanel('admin'))),
            'A role created inside a team granted access to the global admin panel.',
        );
    }

    /**
     * Two teams may each define a role of the same name without colliding —
     * the unique constraint widens to include the team rather than staying on
     * name plus guard.
     */
    public function test_two_teams_may_each_define_a_role_of_the_same_name(): void
    {
        [, $teamA, $teamB] = $this->userInTwoTeams();

        $a = $this->inTeam($teamA, fn () => $this->role('editor'));
        $b = $this->inTeam($teamB, fn () => $this->role('editor'));

        $this->assertNotSame($a->id, $b->id, 'The second team reused the first team\'s role row.');
    }

    /**
     * @return array{0: User, 1: int, 2: int}
     */
    private function userInTwoTeams(): array
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $user = User::factory()->withPersonalTeam()->create();

        $owner->currentTeam->users()->attach($user, ['role' => 'editor']);

        return [$user->fresh(), $user->current_team_id, $owner->current_team_id];
    }

    /**
     * Run a closure with the permission team set, restoring it afterwards. The
     * roles relation is cached per model instance, so callers pass a fresh user
     * rather than reusing one across two team contexts.
     */
    private function inTeam(int $teamId, callable $fn): mixed
    {
        $registrar = app(PermissionRegistrar::class);
        $previous = $registrar->getPermissionsTeamId();

        $registrar->setPermissionsTeamId($teamId);

        try {
            return $fn();
        } finally {
            $registrar->setPermissionsTeamId($previous);
        }
    }

    /**
     * findOrCreate, not Eloquent's firstOrCreate: only the former consults the
     * team context. firstOrCreate matches on name and guard alone, so it hands
     * back another team's role rather than creating one here — which is how an
     * earlier version of this file appeared to prove roles were shared when it
     * was only proving its own helper ignored teams.
     */
    private function role(string $name): Role
    {
        return Role::findOrCreate($name, 'web');
    }
}
