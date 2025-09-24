<?php

namespace App\Filament\App\Resources\PersonNameResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonName extends EditRecord
{
    protected static string $resource = PersonNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
