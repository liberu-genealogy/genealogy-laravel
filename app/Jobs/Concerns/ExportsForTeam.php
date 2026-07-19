<?php

declare(strict_types=1);

namespace App\Jobs\Concerns;

/**
 * The export jobs' shared knowledge of where a team's exports live.
 *
 * Exports were written to the root of the shared private disk under timestamped
 * names, and the export page listed everything there, so every team was shown
 * every other team's exported tree. They live under the team that produced them
 * now, and the page looks no further than its own directory. Both jobs take
 * that path from here so they cannot disagree about it — a second copy drifting
 * is how a file ends up somewhere nothing lists, or somewhere everything does.
 *
 * The "run as a member of the team" half moved to EstablishesTeam, which this
 * pulls in, because it is not export-specific — every single-team job needs it.
 */
trait ExportsForTeam
{
    use EstablishesTeam;

    /**
     * Where a team's exports live. Also the boundary the export page trusts.
     */
    public static function directoryFor(int $teamId): string
    {
        return "exports/{$teamId}";
    }
}
