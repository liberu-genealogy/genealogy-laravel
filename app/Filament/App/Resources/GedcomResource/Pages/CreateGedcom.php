<?php

namespace App\Filament\App\Resources\GedcomResource\Pages;

use App\Filament\App\Resources\GedcomResource;
use App\Filament\App\Resources\ImportJobResource;
use App\Jobs\ImportGedcom;
use App\Jobs\ImportGrampsXml;
use App\Models\ImportJob;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CreateGedcom extends CreateRecord
{
    protected static string $resource = GedcomResource::class;

    /** The import slug created before dispatch, used for the redirect. */
    private string $importSlug = '';

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $path   = $record->filename;

        // FileUpload may store as array (e.g. when Filament uses array persistence)
        if (is_array($path)) {
            $filtered = array_values(array_filter($path));
            $path = $filtered !== [] ? $filtered[0] : null;
        }

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

        $disk = Storage::disk('private');

        $path = (string) $path;

        // If the file landed in livewire-tmp (Livewire's temporary upload directory),
        // move it to the permanent gedcom-form-imports directory so storage is organised
        // correctly and the file survives queue processing.
        if (str_starts_with($path, 'livewire-tmp/') && $disk->exists($path)) {
            $newPath = 'gedcom-form-imports/' . basename($path);
            $disk->move($path, $newPath);
            $record->update(['filename' => $newPath]);
            $path = $newPath;

            Log::info('CreateGedcom: moved upload from livewire-tmp to gedcom-form-imports', [
                'gedcom_id' => $record->getKey(),
                'new_path'  => $newPath,
            ]);
        }

        // Verify the file actually exists before dispatching the job
        if (! $disk->exists($path)) {
            Log::error('CreateGedcom::afterCreate: file does not exist on private disk, aborting dispatch', [
                'gedcom_id' => $record->getKey(),
                'path'      => $path,
            ]);

            Notification::make()
                ->title('Import failed')
                ->body('The uploaded file could not be found. Please try uploading again.')
                ->danger()
                ->send();

            return;
        }

        $fullPath  = $disk->path($path);
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        // Pre-create the ImportJob so the user can track it immediately
        $slug = (string) Str::uuid();
        ImportJob::create([
            'user_id'  => Auth::id(),
            'status'   => 'queue',
            'slug'     => $slug,
            'progress' => 0,
        ]);
        $this->importSlug = $slug;

        try {
            // Dispatch the appropriate import job, passing the pre-created slug
            if (in_array($extension, ['gramps', 'xml'])) {
                ImportGrampsXml::dispatch(Auth::user(), $fullPath, $slug);
                Log::info('Dispatched GrampsXML import', ['path' => $path, 'full_path' => $fullPath, 'slug' => $slug]);
            } else {
                ImportGedcom::dispatch(Auth::user(), $fullPath, $slug);
                Log::info('Dispatched GEDCOM import', ['path' => $path, 'full_path' => $fullPath, 'slug' => $slug]);
            }

            Notification::make()
                ->title('GEDCOM import queued')
                ->body('Your file is being processed. The Import Logs page below shows live progress.')
                ->success()
                ->send();
        } catch (Throwable $e) {
            Log::error('Failed to dispatch GEDCOM import job', [
                'gedcom_id' => $record->getKey(),
                'path'      => $path,
                'full_path' => $fullPath,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title('Import failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * After creation, redirect to Import Logs so the user can watch progress.
     */
    protected function getRedirectUrl(): string
    {
        return ImportJobResource::getUrl('index');
    }
}
