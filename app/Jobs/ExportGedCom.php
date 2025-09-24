<?php

declare(strict_types=1);

namespace App\Jobs;

use Throwable;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use App\Services\GedcomService;
use App\Tenant\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final readonly class ExportGedCom implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $file,
        private readonly User $user,
    ) {}

    public function handle(): void
    {
        try {
            $tenant = Manager::fromModel($this->user->company(), $this->user);
            $tenant->connect();

            $people = Person::all();
            $families = Family::all();

            Log::info("Exporting {$people->count()} people and {$families->count()} families.");

            $gedcomService = new GedcomService();
            $content = $gedcomService->generateGedcomContent($people, $families);

            $tenant->storage()->put($this->file, $content);

            chmod($tenant->storage()->path($this->file), 0644);

            Log::info('GEDCOM file generated and stored successfully.');
        } catch (Throwable $e) {
            Log::error('Error during GEDCOM export: ' . $e->getMessage());
            throw $e;
        }
    }
}