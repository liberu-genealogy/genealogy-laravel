<?php

namespace App\Filament\Resources\PersonNameRomnResource\Pages;

use App\Filament\Resources\PersonNameRomnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonNameRomns extends ListRecords
{
    protected static string $resource = PersonNameRomnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
