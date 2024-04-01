<?php

namespace App\Filament\Resources\NewPersonResource\Pages;

use App\Filament\Resources\NewPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewPerson extends EditRecord
{
    protected static string $resource = NewPersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
