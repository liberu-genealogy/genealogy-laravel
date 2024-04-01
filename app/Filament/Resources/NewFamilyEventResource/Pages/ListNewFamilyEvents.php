<?php

namespace App\Filament\Resources\NewFamilyEventResource\Pages;

use App\Filament\Resources\NewFamilyEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewFamilyEvents extends ListRecords
{
    protected static string $resource = NewFamilyEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
