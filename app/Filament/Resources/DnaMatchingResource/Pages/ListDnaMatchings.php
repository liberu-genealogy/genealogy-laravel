<?php

namespace App\Filament\Resources\DnaMatchingResource\Pages;

use App\Filament\Resources\DnaMatchingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDnaMatchings extends ListRecords
{
    protected static string $resource = DnaMatchingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
