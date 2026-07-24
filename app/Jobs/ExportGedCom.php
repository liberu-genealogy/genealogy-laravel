<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Jobs\Concerns\ExportsForTeam;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use App\Services\GedcomService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class ExportGedCom implements ShouldQueue
{
    use Dispatchable, ExportsForTeam, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $file,
        public readonly User $user,
        public readonly int $teamId,
        private readonly string $format = '5.5.1',
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
            $content = $this->asTeamMember($this->user, $this->teamId, function (): string {
                $people = Person::count();
                $families = Family::count();

                Log::info("Exporting {$people} people and {$families} families for team {$this->teamId}.");

                return match ($this->format) {
                    '7.0' => (new GedcomService)->generateGedcom7Content(),
                    'gedcomx' => (new GedcomService)->generateGedcomXContent(),
                    default => (new GedcomService)->generateGedcomContent(),
                };
            });

            Storage::disk('private')->put($this->path(), $content);

            Log::info('GEDCOM file generated and stored successfully.');
        } catch (Throwable $e) {
            Log::error('Error during GEDCOM export: '.$e->getMessage());
            throw $e;
        }
    }

    private function path(): string
    {
        return self::directoryFor($this->teamId).'/'.$this->file;
    }
}
