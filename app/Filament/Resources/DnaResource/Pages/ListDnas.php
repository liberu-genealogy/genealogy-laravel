<?php

namespace App\Filament\Resources\DnaResource\Pages;

use App\Filament\Resources\DnaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDnas extends ListRecords
{
    protected static string $resource = DnaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
