<?php

namespace App\Jobs;

use Artisan;
use Throwable;
use Exception;
use App\Models\ImportJob;
use App\Models\User;
use App\Tenant\Manager;
use FamilyTree365\LaravelGedcom\Utils\GedcomParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportGedcom extends BaseImportJob
{
    protected function performImport(ImportJob $job, string $slug): void
    {
        $parser = new GedcomParser();
        $team_id = $this->user->currentTeam?->id;
        $parser->parse($job->getConnectionName(), $this->filePath, $slug, true, $team_id);
    }
}
