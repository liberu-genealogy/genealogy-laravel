# Creating a super administrator

The admin panel (`/admin`) is global — it is not scoped to any team. A super
administrator is a user who holds a **team-less** `super_admin` role (a role row
with `team_id = NULL`). `User::hasGlobalRole()` only accepts a team-less role, so
only a team-less `super_admin` opens the admin panel.

## The supported route

```bash
php artisan app:grant-super-admin <email>
```

This finds (or creates) the team-less `super_admin` role and assigns it to the
user with that email. The account can then open `/admin` from any team it belongs
to. A fresh install also seeds this same team-less role (`RolesSeeder`).

## Do not use `shield:super-admin`

This project enables team-scoped roles (`config/permission.php` → `'teams' =>
true`). Under that flag, Filament Shield's `shield:super-admin` requires a
`--tenant` and creates a `super_admin` scoped to **that team** — a role with a
non-null `team_id`, which `hasGlobalRole()` rejects. It would report "Success!"
and hand back an administrator who cannot reach the admin panel.

The command is therefore overridden
(`App\Console\Commands\DisabledShieldSuperAdminCommand`): running it refuses and
points back here. Nothing to do — just use `app:grant-super-admin`.

`shield:setup` shells out to `shield:super-admin` for its optional "create a
super admin" step, so that step will report a failure. That is intended — it
would otherwise create the same broken team-scoped admin. Run
`app:grant-super-admin` afterwards.

## `shield:generate` is safe to re-run

`shield:generate` also reads the teams flag. Because Shield's `tenant_model` is
left unset, it refreshes a single team-less `super_admin`, not one per team, so
re-running it on an installed system does not litter the roles table. (If anyone
sets `tenant_model` in a published `config/filament-shield.php`, that stops being
true and `shield:generate` would create a per-team `super_admin` for every team —
`VendorSuperAdminCommandsTest` guards against that regression.)
