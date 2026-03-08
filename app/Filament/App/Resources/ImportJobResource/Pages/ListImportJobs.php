<?php

namespace App\Filament\App\Resources\ImportJobResource\Pages;

use App\Filament\App\Resources\ImportJobResource;
use Filament\Resources\Pages\ListRecords;

class ListImportJobs extends ListRecords
{
    protected static string $resource = ImportJobResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
