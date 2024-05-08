<?php

namespace App\Filament\Resources\NewPersonAnciResource\Pages;

use App\Filament\Resources\NewPersonAnciResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewPersonAncis extends ListRecords
{
    protected static string $resource = NewPersonAnciResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
