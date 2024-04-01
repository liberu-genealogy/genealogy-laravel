<?php

namespace App\Filament\Resources\NewFamilyEventResource\Pages;

use App\Filament\Resources\NewFamilyEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewFamilyEvent extends EditRecord
{
    protected static string $resource = NewFamilyEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
