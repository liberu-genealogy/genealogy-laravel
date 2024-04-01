<?php

namespace App\Filament\Resources\NewPersonAliaResource\Pages;

use App\Filament\Resources\NewPersonAliaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewPersonAlias extends ListRecords
{
    protected static string $resource = NewPersonAliaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
