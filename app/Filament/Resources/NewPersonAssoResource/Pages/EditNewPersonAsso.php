<?php

namespace App\Filament\Resources\NewPersonAssoResource\Pages;

use App\Filament\Resources\NewPersonAssoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewPersonAsso extends EditRecord
{
    protected static string $resource = NewPersonAssoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
