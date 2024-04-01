<?php

namespace App\Filament\Resources\NewFamilyResource\Pages;

use App\Filament\Resources\NewFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewFamilies extends ListRecords
{
    protected static string $resource = NewFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
