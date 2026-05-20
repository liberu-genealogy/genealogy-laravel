<?php

namespace App\Filament\App\Resources\PersonResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeople extends ListRecords
{
    protected static string $resource = PersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
