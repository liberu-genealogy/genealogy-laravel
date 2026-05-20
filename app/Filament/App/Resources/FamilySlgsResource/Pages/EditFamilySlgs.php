<?php

namespace App\Filament\App\Resources\FamilySlgsResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\FamilySlgsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamilySlgs extends EditRecord
{
    protected static string $resource = FamilySlgsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
