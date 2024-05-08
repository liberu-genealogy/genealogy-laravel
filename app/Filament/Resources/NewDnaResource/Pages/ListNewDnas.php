<?php

namespace App\Filament\Resources\NewDnaResource\Pages;

use App\Filament\Resources\NewDnaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewDnas extends ListRecords
{
    protected static string $resource = NewDnaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
