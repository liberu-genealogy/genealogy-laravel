<?php

namespace App\Filament\App\Resources\ImportJobResource\Pages;

use App\Filament\App\Resources\ImportJobResource;
use Filament\Resources\Pages\ListRecords;

class ListImportJobs extends ListRecords
{
    protected static string $resource = ImportJobResource::class;

    /** Auto-refresh every 3 seconds so in-progress imports update live. */
    protected static ?string $pollingInterval = '3s';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
