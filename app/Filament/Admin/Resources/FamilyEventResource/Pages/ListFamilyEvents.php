<?php

namespace App\Filament\Resources\FamilyEventResource\Pages;

use App\Filament\Resources\FamilyEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilyEvents extends ListRecords
{
    protected static string $resource = FamilyEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
