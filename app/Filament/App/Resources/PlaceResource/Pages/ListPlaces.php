<?php

namespace App\Filament\App\Resources\PlaceResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PlaceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlaces extends ListRecords
{
    protected static string $resource = PlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
