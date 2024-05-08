<?php

namespace App\Filament\Resources\NewFamilySlgsResource\Pages;

use App\Filament\Resources\NewFamilySlgsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewFamilySlgs extends EditRecord
{
    protected static string $resource = NewFamilySlgsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
