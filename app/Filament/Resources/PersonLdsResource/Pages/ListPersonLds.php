<?php

namespace App\Filament\Resources\PersonLdsResource\Pages;

use App\Filament\Resources\PersonLdsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonLds extends ListRecords
{
    protected static string $resource = PersonLdsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
