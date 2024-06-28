<?php

namespace App\Filament\Resources\GedcomResource\Pages;

use App\Filament\Resources\GedcomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGedcom extends EditRecord
{
    protected static string $resource = GedcomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
