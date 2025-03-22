<?php

namespace App\Jobs;

use App\Models\{Family, Person, User};
use App\Services\GedcomService;
use App\Tenant\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

final class ExportGedCom implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $file,
        private readonly User $user
    ) {}

    public function handle(GedcomService $gedcomService): void
    {
        $tenant = Manager::fromModel($this->user->company(), $this->user);
        $tenant->connect();

        try {
            $people = Person::all();
            $families = Family::all();

            Log::info("Exporting {$people->count()} people and {$families->count()} families");

            $content = $gedcomService->generateGedcomContent($people, $families);
            $tenant->storage()->put($this->file, $content);

            $this->setFilePermissions($tenant);

            Log::info("GEDCOM file generated: {$this->file}");
        } catch (\Throwable $e) {
            Log::error("GEDCOM export failed: {$e->getMessage()}");
            throw $e;
        }
    }

    private function setFilePermissions(Manager $tenant): void
    {
        chmod($tenant->storage()->path($this->file), 0644);
    }
}