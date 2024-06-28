<?php

namespace App\Filament\Resources\PersonAssoResource\Pages;

use App\Filament\Resources\PersonAssoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonAssos extends ListRecords
{
    protected static string $resource = PersonAssoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
