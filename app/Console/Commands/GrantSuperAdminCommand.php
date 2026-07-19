<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\PermissionRegistrar;

/**
 * Grants super_admin, which is the one role that has to work in every team.
 *
 * This exists because Shield's own `shield:super-admin` no longer produces a
 * working one. Shield reads the same permission.teams flag, and with it on the
 * command requires a --tenant and creates the role scoped to that team. A
 * team-scoped role named super_admin does not open the admin panel — deliberately,
 * since the panel is global and anything created from inside a team must not be
 * able to claim it (User::hasGlobalRole). So Shield's command now yields an
 * administrator who cannot administer, with nothing to say why.
 *
 * Seeding is the only other route, and that only fires on a fresh install.
 */
class GrantSuperAdminCommand extends Command
{
    protected $signature = 'app:grant-super-admin {email : The email address of the user to promote}';

    protected $description = 'Grant a user the team-less super_admin role';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error("No user with the email {$this->argument('email')}.");

            return self::FAILURE;
        }

        $team = $user->currentTeam ?? $user->allTeams()->first();

        if (! $team) {
            $this->error("{$user->email} belongs to no team, and a role grant has to name one.");

            return self::FAILURE;
        }

        $roleModel = config('permission.models.role');
        $teamKey = config('permission.column_names.team_foreign_key', 'team_id');

        // firstOrCreate on the team key too: without it this matches a
        // team-scoped super_admin that someone made earlier and grants that
        // instead, which is the exact non-working role this command exists to
        // avoid handing out.
        $role = $roleModel::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
            $teamKey => null,
        ]);

        // The role is team-less; the grant of it still has to name a team.
        app(PermissionRegistrar::class)->setPermissionsTeamId($team->getKey());
        $user->assignRole($role);

        $this->info("{$user->email} is now a super admin.");

        return self::SUCCESS;
    }
}
