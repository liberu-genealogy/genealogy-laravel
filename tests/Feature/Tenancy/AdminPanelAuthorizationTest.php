<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * The admin panel has no tenancy, so nothing about it may depend on which team
 * the administrator happens to be working in.
 *
 * Gating the panel door on a team-less role was only half of that. The 53
 * policies behind the door resolve through checkPermissionTo(), which walks the
 * roles relation, which the permission library filters by the current team
 * unconditionally. So an administrator whose grant lived in one team and whose
 * current team was another would pass the door and be refused by every resource
 * inside it — a panel that renders its navigation and then 403s on every page,
 * which is worse than being turned away at the door.
 */
class AdminPanelAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_keeps_their_permissions_after_switching_team(): void
    {
        [$user, $ownTeam, $otherTeam] = $this->administratorInTwoTeams();

        $this->assertTrue(
            $user->fresh()->checkPermissionTo('view-any ImportJob'),
            'Fixture is degenerate: the permission does not hold in the granting team.',
        );

        // Working in the other team, as after any use of the team switcher.
        app(PermissionRegistrar::class)->setPermissionsTeamId($otherTeam);

        $fresh = $user->fresh();

        $this->assertTrue(
            $fresh->canAccessPanel(Filament::getPanel('admin')),
            'The panel door closed on an administrator.',
        );

        $this->assertTrue(
            $fresh->checkPermissionTo('view-any ImportJob'),
            'The administrator passed the panel door and was refused by the resources behind it.',
        );
    }

    /**
     * The counterpart. A team-scoped role must NOT leak its permissions into the
     * admin panel, or the fix above becomes a way to grant global rights from
     * inside a team.
     */
    public function test_a_team_scoped_role_does_not_confer_admin_permissions(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $permission = Permission::firstOrCreate(['name' => 'view-any ImportJob', 'guard_name' => 'web']);

        app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);

        $scoped = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->assertNotNull($scoped->team_id, 'Fixture is degenerate: the role is team-less.');
        $scoped->givePermissionTo($permission);
        $user->assignRole($scoped);

        $this->assertFalse(
            $user->fresh()->canAccessPanel(Filament::getPanel('admin')),
            'A role created inside a team opened the admin panel.',
        );
    }

    /**
     * @return array{0: User, 1: int, 2: int}
     */
    private function administratorInTwoTeams(): array
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $user = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($user, ['role' => 'editor']);

        $permission = Permission::firstOrCreate(['name' => 'view-any ImportJob', 'guard_name' => 'web']);

        // Team-less, as the seeder creates it.
        $role = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        $user = $user->fresh();
        app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);
        $user->assignRole($role);

        return [$user, $user->current_team_id, $owner->current_team_id];
    }
}
