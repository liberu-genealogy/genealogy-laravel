<?php

namespace App\Filament\Resources\NewFamilyResource\Pages;

use App\Filament\Resources\NewFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewFamily extends EditRecord
{
    protected static string $resource = NewFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
