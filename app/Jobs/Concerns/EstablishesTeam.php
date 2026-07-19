<?php

declare(strict_types=1);

namespace App\Jobs\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

/**
 * Runs a job body as a member of a specific team, so tenant scoping is active.
 *
 * The tenant scope (BelongsToTenant) reads the authenticated user's current
 * team and returns early when nobody is authenticated. A queue worker has no
 * authenticated user, so a job sees every team's rows and stamps new rows with
 * no team at all. This authenticates the job's user, pinned to the team the
 * work belongs to, for the duration of the callback and restores whatever the
 * worker had before.
 *
 * For SINGLE-TEAM jobs — an import or export that belongs to one team. The
 * cross-team analytical jobs (DNA matching, record matching, dedupe) must read
 * across every team and must NOT use this; they stamp team_id per written row
 * from the owning entity instead.
 *
 * Extracted from ExportsForTeam, which now uses it. The permission-registrar
 * handling below is new: it belongs with establishing the team, not with where
 * an export file goes.
 */
trait EstablishesTeam
{
    /**
     * Run a callback authenticated as the given user, pinned to the given team,
     * restoring the worker's previous auth and permission-team afterwards.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    protected function asTeamMember(User $user, int $teamId, callable $callback): mixed
    {
        $previousUser = Auth::user();
        $registrar = app(PermissionRegistrar::class);
        $previousTeam = $registrar->getPermissionsTeamId();

        // A user can belong to several teams, so a copy pinned to this team is
        // used rather than the user as-is — otherwise a job would scope to
        // whatever team the user last worked in. Only this copy is adjusted;
        // nothing is saved.
        $scoped = $user->replicate();
        $scoped->id = $user->id;
        $scoped->exists = true;
        $scoped->current_team_id = $teamId;

        Auth::login($scoped);

        // The permission library also holds the current team, on a singleton
        // that a queue worker never resets (its only reset listener is
        // Octane-only). Set it here so any role or permission check inside the
        // job resolves against this team, and restore it below so the value
        // does not leak into the next job in the same worker.
        $registrar->setPermissionsTeamId($teamId);

        try {
            return $callback();
        } finally {
            Auth::logout();
            $registrar->setPermissionsTeamId($previousTeam);

            if ($previousUser) {
                Auth::login($previousUser);
            }
        }
    }
}
