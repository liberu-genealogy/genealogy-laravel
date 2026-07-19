<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Jobs\Concerns\ExportsForTeam;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use App\Services\GrampsXmlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class ExportGrampsXml implements ShouldQueue
{
    use Dispatchable, ExportsForTeam, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $file,
        private User $user,
        private int $teamId,
    ) {}

    /**
     * Exports one team's tree, into that team's own directory. The reasoning is
     * in ExportsForTeam; the leak was plainer here than in the GEDCOM export,
     * since Person::all() is handed straight to the generator.
     */
    public function handle(): void
    {
        try {
            $content = $this->asTeamMember($this->user, $this->teamId, function (): string {
                $people = Person::all();
                $families = Family::all();

                Log::info("Exporting {$people->count()} people and {$families->count()} families to GrampsXML for team {$this->teamId}.");

                return (new GrampsXmlService)->generateGrampsXmlContent($people, $families);
            });

            Storage::disk('private')->put(self::directoryFor($this->teamId).'/'.$this->file, $content);

            Log::info('GrampsXML file generated and stored successfully.');
        } catch (Throwable $e) {
            Log::error('Error during GrampsXML export: '.$e->getMessage());
            throw $e;
        }
    }
}
