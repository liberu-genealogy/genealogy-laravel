<?php

declare(strict_types=1);

namespace App\Jobs\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Shared by the export jobs, which both have to solve the same two problems.
 *
 * Where the file goes. Exports were written to the root of the shared private
 * disk under timestamped names, and the export page listed everything there, so
 * every team was shown every other team's exported tree. They live under the
 * team that produced them now, and the page looks no further than its own
 * directory. Both jobs take that path from here so they cannot disagree about
 * it — a second copy drifting is how a file ends up somewhere nothing lists, or
 * somewhere everything does.
 *
 * What goes in it. The generators query through the Person and Family models,
 * whose tenant scope is a global scope that returns early when nobody is
 * authenticated — and nothing is authenticated inside a queued job. So an
 * export walked every team's records into one file. Authenticating the
 * requesting user, pinned to the team being exported, puts the scope back in
 * play for the duration.
 *
 * That second part is a local answer to a general problem: no job in this
 * application establishes a tenant, and the rest are scoped by luck or not at
 * all. Tenant-isolation #08 is where that gets solved properly.
 */
trait ExportsForTeam
{
    /**
     * Where a team's exports live. Also the boundary the export page trusts.
     */
    public static function directoryFor(int $teamId): string
    {
        return "exports/{$teamId}";
    }

    /**
     * Run a callback with the exporting user authenticated against the team
     * being exported, restoring whatever the worker had before.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    protected function asTeamMember(User $user, int $teamId, callable $callback): mixed
    {
        $previous = Auth::user();

        // The tenant scope reads the current team, and a user can belong to
        // several — so exporting while they happen to be working elsewhere
        // would produce the wrong family's records under this team's directory.
        // Only this job's copy is adjusted; nothing is saved.
        $scoped = $user->replicate();
        $scoped->id = $user->id;
        $scoped->exists = true;
        $scoped->current_team_id = $teamId;

        Auth::login($scoped);

        try {
            return $callback();
        } finally {
            // A worker serves many jobs in one process; leaving this set would
            // scope the next job to whoever ran this one.
            Auth::logout();

            if ($previous) {
                Auth::login($previous);
            }
        }
    }
}
