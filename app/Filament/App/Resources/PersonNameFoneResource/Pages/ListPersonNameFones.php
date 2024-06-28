<?php

namespace App\Filament\Resources\PersonNameFoneResource\Pages;

use App\Filament\Resources\PersonNameFoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonNameFones extends ListRecords
{
    protected static string $resource = PersonNameFoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
