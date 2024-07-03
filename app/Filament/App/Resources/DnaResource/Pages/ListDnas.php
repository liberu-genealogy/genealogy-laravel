<?php

namespace App\Filament\App\Resources\DnaResource\Pages;

use App\Filament\App\Resources\DnaResource;
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
