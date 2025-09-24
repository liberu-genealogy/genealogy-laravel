<?php

namespace App\Filament\App\Resources\PersonResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerson extends EditRecord
{
    protected static string $resource = PersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
