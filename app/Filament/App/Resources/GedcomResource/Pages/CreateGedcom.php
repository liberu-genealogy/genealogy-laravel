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
use Throwable;

class CreateGedcom extends CreateRecord
{
    protected static string $resource = GedcomResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $path = $record->filename;

        Log::info('CreateGedcom::afterCreate called', [
            'gedcom_id' => $record->getKey(),
            'filename'  => $path,
        ]);

        if (! $path) {
            Log::warning('CreateGedcom::afterCreate: filename is empty, skipping dispatch', [
                'gedcom_id' => $record->getKey(),
            ]);

            return;
        }

        $fullPath = Storage::disk('private')->path($path);
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        try {
            // Dispatch appropriate import job based on file extension
            if (in_array($extension, ['gramps', 'xml'])) {
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
        } catch (Throwable $e) {
            Log::error('Failed to dispatch GEDCOM import job', [
                'gedcom_id'  => $record->getKey(),
                'path'       => $path,
                'full_path'  => $fullPath,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
