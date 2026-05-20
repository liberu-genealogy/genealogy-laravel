<?php

namespace App\Filament\App\Resources\FamilyEventResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\FamilyEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamilyEvent extends EditRecord
{
    protected static string $resource = FamilyEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
