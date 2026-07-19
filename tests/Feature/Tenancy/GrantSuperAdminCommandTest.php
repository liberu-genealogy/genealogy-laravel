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
 * The command has to produce an administrator who can actually administer.
 *
 * Asserting that a role row was written would not show that: a team-scoped
 * super_admin is a perfectly ordinary row and is exactly the useless result
 * this command exists to avoid. So these assert on panel access instead.
 */
class GrantSuperAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_grants_a_role_that_opens_the_admin_panel_from_any_team(): void
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $user = User::factory()->withPersonalTeam()->create();
        $owner->currentTeam->users()->attach($user, ['role' => 'editor']);

        $this->artisan('app:grant-super-admin', ['email' => $user->email])->assertSuccessful();

        $panel = Filament::getPanel('admin');

        foreach ([$user->current_team_id, $owner->current_team_id] as $teamId) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($teamId);

            $this->assertTrue(
                $user->fresh()->canAccessPanel($panel),
                "The granted role did not open the admin panel from team {$teamId}.",
            );
        }
    }

    /**
     * A team-scoped super_admin left over from Shield's command must not be
     * picked up and handed back — it would report success and grant the role
     * that does not work.
     */
    public function test_it_does_not_reuse_a_team_scoped_role_of_the_same_name(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);
        $scoped = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->assertNotNull($scoped->team_id, 'Fixture is degenerate: the decoy role is team-less.');

        $this->artisan('app:grant-super-admin', ['email' => $user->email])->assertSuccessful();

        $this->assertTrue(
            $user->fresh()->canAccessPanel(Filament::getPanel('admin')),
            'The command reused the team-scoped role and granted no real access.',
        );
    }

    public function test_it_fails_on_an_unknown_email(): void
    {
        $this->artisan('app:grant-super-admin', ['email' => 'nobody@example.com'])->assertFailed();
    }
}
