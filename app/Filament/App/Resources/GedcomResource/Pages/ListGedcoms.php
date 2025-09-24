<?php

namespace App\Filament\App\Resources\GedcomResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\GedcomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGedcoms extends ListRecords
{
    protected static string $resource = GedcomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
