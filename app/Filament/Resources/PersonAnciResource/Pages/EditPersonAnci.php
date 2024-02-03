<?php

namespace App\Filament\Resources\PersonAnciResource\Pages;

use App\Filament\Resources\PersonAnciResource;
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
