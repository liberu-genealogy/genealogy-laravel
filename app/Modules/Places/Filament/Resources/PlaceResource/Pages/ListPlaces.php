<?php

declare(strict_types=1);

namespace App\Modules\Places\Filament\Resources\PlaceResource\Pages;

use App\Modules\Places\Filament\Resources\PlaceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlaces extends ListRecords
{
    #[\Override]
    protected static string $resource = PlaceResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
