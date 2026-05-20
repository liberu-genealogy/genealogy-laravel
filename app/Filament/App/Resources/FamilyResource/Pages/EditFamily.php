<?php

namespace App\Filament\App\Resources\FamilyResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\FamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamily extends EditRecord
{
    protected static string $resource = FamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
