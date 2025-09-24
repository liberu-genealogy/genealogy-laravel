<?php

namespace App\Filament\App\Resources\DnaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\DnaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDnas extends ListRecords
{
    protected static string $resource = DnaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
