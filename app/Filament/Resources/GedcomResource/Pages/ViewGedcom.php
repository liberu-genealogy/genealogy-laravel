<?php

namespace App\Filament\Resources\GedcomResource\Pages;

use App\Filament\Resources\GedcomResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGedcom extends ViewRecord
{
    protected static string $resource = GedcomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
