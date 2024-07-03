<?php

namespace App\Filament\App\Resources\PersonNameRomnResource\Pages;

use App\Filament\App\Resources\PersonNameRomnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonNameRomn extends EditRecord
{
    protected static string $resource = PersonNameRomnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
