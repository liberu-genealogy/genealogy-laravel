<?php

namespace App\Filament\Resources\NewPersonAssoResource\Pages;

use App\Filament\Resources\NewPersonAssoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewPersonAssos extends ListRecords
{
    protected static string $resource = NewPersonAssoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
