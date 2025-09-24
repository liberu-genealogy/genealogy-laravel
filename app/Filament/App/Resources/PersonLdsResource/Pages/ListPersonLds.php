<?php

namespace App\Filament\App\Resources\PersonLdsResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonLdsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonLds extends ListRecords
{
    protected static string $resource = PersonLdsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
