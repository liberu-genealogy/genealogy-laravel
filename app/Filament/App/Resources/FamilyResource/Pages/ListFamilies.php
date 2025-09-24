<?php

namespace App\Filament\App\Resources\FamilyResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\FamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilies extends ListRecords
{
    protected static string $resource = FamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
