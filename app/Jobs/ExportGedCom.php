<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use App\Services\GedcomService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class ExportGedCom implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $file,
        public readonly User $user,
        public readonly int $teamId,
    ) {}

    /**
     * Exports one team's tree, into that team's own directory.
     *
     * Both halves of that were missing and each was a cross-tenant leak on its
     * own.
     *
     * The file went to the root of the shared private disk under a name made
     * only of a timestamp, and the export page listed everything there — so
     * every team was shown, and could download, every other team's exported
     * tree. Files now live under the team that produced them, and the page
     * looks no further than its own directory.
     *
     * The contents were worse. The generator queries through the Person and
     * Family models, whose tenant scope is a global scope that returns early
     * when nobody is authenticated — and nothing is authenticated inside a
     * queued job. So an export walked every team's records into a single file.
     * Authenticating the requesting user for the duration of the generation
     * puts the scope back in play.
     *
     * That last part is a local fix to a general problem: no job in this
     * application establishes a tenant, and the others are scoped by luck or
     * not at all. Tenant-isolation #08 is where that gets solved properly. This
     * is not waiting for it, because the file it produces is the single richest
     * artefact the application holds.
     */
    public function handle(): void
    {
        try {
            $previous = Auth::user();

            Auth::login($this->exportingUser());

            try {
                $people = Person::count();
                $families = Family::count();

                Log::info("Exporting {$people} people and {$families} families for team {$this->teamId}.");

                $content = (new GedcomService)->generateGedcomContent();
            } finally {
                // A worker serves many jobs in one process; leaving this set
                // would scope the next job to whoever ran this one.
                Auth::logout();

                if ($previous) {
                    Auth::login($previous);
                }
            }

            Storage::disk('private')->put($this->path(), $content);

            Log::info('GEDCOM file generated and stored successfully.');
        } catch (Throwable $e) {
            Log::error('Error during GEDCOM export: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Where this team's exports live. Also the boundary the export page trusts,
     * so it is defined here rather than assembled at each call site.
     */
    public static function directoryFor(int $teamId): string
    {
        return "exports/{$teamId}";
    }

    private function path(): string
    {
        return self::directoryFor($this->teamId).'/'.$this->file;
    }

    /**
     * The requesting user, with their current team pinned to the team being
     * exported.
     *
     * The tenant scope reads the current team, and a user can belong to several
     * — so exporting while they happen to be working elsewhere would otherwise
     * produce a file full of the wrong family's records under this team's
     * directory. The instance is not saved; only this job's copy is adjusted.
     */
    private function exportingUser(): User
    {
        $user = $this->user->replicate();
        $user->id = $this->user->id;
        $user->exists = true;
        $user->current_team_id = $this->teamId;

        return $user;
    }
}
