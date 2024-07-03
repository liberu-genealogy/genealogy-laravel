<?php

namespace App\Filament\App\Resources\SourceDataEvenResource\Pages;

use App\Filament\App\Resources\SourceDataEvenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceDataEvens extends ListRecords
{
    protected static string $resource = SourceDataEvenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
