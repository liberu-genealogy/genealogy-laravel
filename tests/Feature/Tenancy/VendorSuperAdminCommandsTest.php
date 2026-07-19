<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Models\Team;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Enabling team-scoped roles (permission.teams = true) changed how the Shield
 * vendor commands behave. These pin the two that mattered:
 *
 *  - shield:super-admin would mint a *team-scoped* super_admin, which
 *    User::hasGlobalRole() rejects — a command that reports success and hands
 *    back an administrator who cannot administer. It is overridden
 *    (App\Console\Commands\DisabledShieldSuperAdminCommand) to refuse and point
 *    at app:grant-super-admin.
 *  - shield:generate re-runs the super_admin role creation. With Shield's
 *    tenant_model unset it must produce one team-less role, not one per team.
 */
class VendorSuperAdminCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_vendor_super_admin_command_is_refused_and_names_the_working_route(): void
    {
        $code = Artisan::call('shield:super-admin', ['--tenant' => 1]);

        $this->assertSame(Command::FAILURE, $code, 'shield:super-admin did not fail.');
        $this->assertStringContainsString(
            'app:grant-super-admin',
            Artisan::output(),
            'shield:super-admin failed without naming the working alternative.',
        );
    }

    /**
     * This calls the exact vendor code shield:generate runs to grant the
     * super_admin its permissions (Utils::giveSuperAdminPermission), rather than
     * the whole command — in the test panel Shield discovers no entities, so the
     * command itself never reaches this branch. The branch is what matters: with
     * several teams present and Shield's tenant_model unset it must create one
     * team-less super_admin, not one per team. If tenant_model is ever set, the
     * per-tenant loop reappears and this fails, flagging the roles-table clutter
     * the ticket warns about before it ships.
     */
    public function test_regenerating_permissions_never_creates_a_team_scoped_super_admin(): void
    {
        Team::factory()->count(3)->create();
        Permission::create(['name' => 'view_any_person', 'guard_name' => 'web']);

        Utils::giveSuperAdminPermission(['view_any_person']);

        $superAdmins = Role::where('name', 'super_admin')->get();

        $this->assertCount(
            1,
            $superAdmins,
            "Expected one team-less super_admin; got {$superAdmins->count()} — a per-team role means the roles table now has rows that confer no administration.",
        );
        $this->assertNull(
            $superAdmins->first()->team_id,
            'shield:generate created a team-scoped super_admin, which User::hasGlobalRole() rejects.',
        );
    }
}
