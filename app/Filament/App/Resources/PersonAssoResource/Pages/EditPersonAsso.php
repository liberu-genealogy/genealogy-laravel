<?php

namespace App\Filament\App\Resources\PersonAssoResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonAssoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonAsso extends EditRecord
{
    protected static string $resource = PersonAssoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
