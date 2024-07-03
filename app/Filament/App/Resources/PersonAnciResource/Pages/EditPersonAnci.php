<?php

namespace App\Filament\App\Resources\PersonAnciResource\Pages;

use App\Filament\App\Resources\PersonAnciResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonAnci extends EditRecord
{
    protected static string $resource = PersonAnciResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
