<?php

namespace App\Filament\App\Resources\GedcomResource\Pages;

use App\Filament\App\Resources\GedcomResource;
use App\Jobs\ImportGedcom;
use App\Jobs\ImportGrampsXml;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateGedcom extends CreateRecord
{
    protected static string $resource = GedcomResource::class;

    protected function afterCreate(): void
    {
        parent::afterCreate();

        $record = $this->getRecord();

        $files = (array) data_get($record, 'filename', []);
        if (empty($files)) {
            return;
        }

        $disk = Storage::disk('private');

        foreach ($files as $path) {
            if (! $disk->exists($path)) {
                Log::warning("Gedcom upload exists on model but file missing: {$path}");
                continue;
            }

            $fullPath = $disk->path($path) ?? $path;
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

            if (in_array($extension, ['gramps', 'xml'], true)) {
                ImportGrampsXml::dispatch(Auth::user(), $fullPath);
                Log::info('Dispatched GrampsXML import', ['path' => $path, 'full_path' => $fullPath]);
            } else {
                ImportGedcom::dispatch(Auth::user(), $fullPath);
                Log::info('Dispatched GEDCOM import', ['path' => $path, 'full_path' => $fullPath]);
            }

            Notification::make()
                ->title('GEDCOM import queued')
                ->body('Your file is being processed. Check Import Logs to monitor progress.')
                ->success()
                ->send();
        }
    }
}
