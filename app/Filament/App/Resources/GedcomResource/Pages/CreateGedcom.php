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
        // Runs before the form fields are saved to the database.
        // $path =  $this->data['filename'];
        Log::info($this->getRecord());
        foreach ($this->data['filename'] as $key => $path) {
            Log::info($path);
            ImportGedcom::dispatch(Auth::user(), Storage::disk('private')->path($path));
        }
    }
}
