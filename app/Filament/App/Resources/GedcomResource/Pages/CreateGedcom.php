<?php

namespace App\Filament\App\Resources\GedcomResource\Pages;

use App\Filament\App\Resources\GedcomResource;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\ImportGedcom;
use App\Jobs\ImportGrampsXml;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateGedcom extends CreateRecord
{
    protected static string $resource = GedcomResource::class;

    protected function afterCreate(): void
    {
        // Dispatch import for the stored file(s) based on file extension
        $files = (array)($this->data['filename'] ?? []);
        foreach ($files as $path) {
            $fullPath = Storage::disk('private')->path($path);
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            
            // Dispatch appropriate import job based on file extension
            if (in_array($extension, ['gramps', 'xml'])) {
                ImportGrampsXml::dispatch(Auth::user(), $fullPath);
                Log::info("Dispatched GrampsXML import for: {$path}");
            } else {
                ImportGedcom::dispatch(Auth::user(), $fullPath);
                Log::info("Dispatched GEDCOM import for: {$path}");
            }
        }
    }
}
