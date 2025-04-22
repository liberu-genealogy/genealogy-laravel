<?php

namespace App\Filament\App\Resources\PersonNameFoneResource\Pages;

use App\Filament\App\Resources\PersonNameFoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonNameFone extends EditRecord
{
    protected static string $resource = PersonNameFoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
