<?php

namespace App\Filament\Resources\FamilySlgsResource\Pages;

use App\Filament\Resources\FamilySlgsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamilySlgs extends EditRecord
{
    protected static string $resource = FamilySlgsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
