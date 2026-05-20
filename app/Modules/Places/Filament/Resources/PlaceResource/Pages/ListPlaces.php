<?php

namespace App\Modules\Places\Filament\Resources\PlaceResource\Pages;

use Filament\Actions\CreateAction;
use App\Modules\Places\Filament\Resources\PlaceResource;
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