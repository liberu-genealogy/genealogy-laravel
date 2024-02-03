<?php

namespace App\Filament\Resources\PersonAssoResource\Pages;

use App\Filament\Resources\PersonAssoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonAsso extends EditRecord
{
    protected static string $resource = PersonAssoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
