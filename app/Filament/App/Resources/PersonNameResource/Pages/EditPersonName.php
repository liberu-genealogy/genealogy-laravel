<?php

namespace App\Filament\App\Resources\PersonNameResource\Pages;

use App\Filament\App\Resources\PersonNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonName extends EditRecord
{
    protected static string $resource = PersonNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
