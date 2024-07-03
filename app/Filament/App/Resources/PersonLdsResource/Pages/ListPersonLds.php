<?php

namespace App\Filament\App\Resources\PersonLdsResource\Pages;

use App\Filament\App\Resources\PersonLdsResource;
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
