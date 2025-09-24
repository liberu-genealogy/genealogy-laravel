<?php

namespace App\Filament\App\Resources\FamilyEventResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\FamilyEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilyEvents extends ListRecords
{
    protected static string $resource = FamilyEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
