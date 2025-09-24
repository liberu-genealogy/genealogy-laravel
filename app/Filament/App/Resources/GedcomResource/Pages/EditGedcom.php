<?php

namespace App\Filament\App\Resources\GedcomResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\GedcomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGedcom extends EditRecord
{
    protected static string $resource = GedcomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
