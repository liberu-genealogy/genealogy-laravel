<?php

namespace App\Filament\App\Resources\PersonNameRomnResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonNameRomnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonNameRomns extends ListRecords
{
    protected static string $resource = PersonNameRomnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
