<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\ImportJobResource\Pages;

use App\Filament\Admin\Resources\ImportJobResource;
use Filament\Resources\Pages\ListRecords;

class ListImportJobs extends ListRecords
{
    #[\Override]
    protected static string $resource = ImportJobResource::class;

    /** Live-refresh so in-progress imports update in the admin monitor. */
    protected static ?string $pollingInterval = '5s';

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [];
    }
}
