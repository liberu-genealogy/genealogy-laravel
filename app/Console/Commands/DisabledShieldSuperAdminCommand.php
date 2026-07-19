<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Overrides bezhanSalleh/filament-shield's `shield:super-admin` by claiming the
 * same command name.
 *
 * With permission.teams = true (this project enables team-scoped roles), the
 * vendor command requires a --tenant and mints a *team-scoped* super_admin.
 * User::hasGlobalRole() only accepts a role whose definition has team_id = NULL,
 * so a team-scoped super_admin never opens the admin panel: the vendor command
 * reports "Success!" and hands back an administrator who cannot administer, with
 * nothing to say what went wrong. Rather than let that happen, refuse and name
 * the route that works. VendorSuperAdminCommandsTest pins that this override,
 * not the vendor command, is what `shield:super-admin` resolves to.
 */
class DisabledShieldSuperAdminCommand extends Command
{
    // Same three options the vendor command accepts, so passing --tenant/--user/
    // --panel reaches this message rather than an "option does not exist" error.
    protected $signature = 'shield:super-admin
        {--user= : Unsupported — use app:grant-super-admin instead.}
        {--panel= : Unsupported — use app:grant-super-admin instead.}
        {--tenant= : Unsupported — use app:grant-super-admin instead.}';

    protected $description = 'Disabled in this project — run app:grant-super-admin <email> instead.';

    public function handle(): int
    {
        $this->components->error(
            'shield:super-admin is disabled here. This project uses team-scoped roles, '
            .'and that command would create a team-scoped super_admin that cannot open the '
            .'admin panel. Run `php artisan app:grant-super-admin <email>` instead — it mints '
            .'the team-less super_admin the admin panel requires.'
        );

        return self::FAILURE;
    }
}
