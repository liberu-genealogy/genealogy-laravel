<?php

declare(strict_types=1);

namespace App\Jobs;

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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $file,
        private User $user,
    ) {}

    public function handle(): void
    {
        try {
            $people = Person::all();
            $families = Family::all();

            Log::info("Exporting {$people->count()} people and {$families->count()} families to GrampsXML.");

            $grampsXmlService = new GrampsXmlService;
            $content = $grampsXmlService->generateGrampsXmlContent($people, $families);

            Storage::disk('private')->put($this->file, $content);

            Log::info('GrampsXML file generated and stored successfully.');
        } catch (Throwable $e) {
            Log::error('Error during GrampsXML export: '.$e->getMessage());
            throw $e;
        }
    }
}
