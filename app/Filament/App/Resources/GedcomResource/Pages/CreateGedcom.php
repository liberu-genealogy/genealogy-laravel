<?php

namespace App\Filament\App\Resources\GedcomResource\Pages;

use App\Filament\App\Resources\GedcomResource;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\ImportGedcom;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateGedcom extends CreateRecord
{
    protected static string $resource = GedcomResource::class;

    protected function afterCreate(): void
    {
        // Dispatch GEDCOM import for the stored file(s)
        $files = (array)($this->data['filename'] ?? []);
        foreach ($files as $path) {
            ImportGedcom::dispatch(Auth::user(), Storage::disk('private')->path($path));
        }
    }
}
