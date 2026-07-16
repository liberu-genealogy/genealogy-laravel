<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\GedcomResource\Pages;

use App\Filament\App\Resources\GedcomResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGedcoms extends ListRecords
{
    #[\Override]
    protected static string $resource = GedcomResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Upload'),
        ];
    }
}
