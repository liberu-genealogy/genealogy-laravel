<?php

namespace App\Filament\Resources\GedcomResource\Pages;

use App\Filament\Resources\GedcomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGedcoms extends ListRecords
{
    protected static string $resource = GedcomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
