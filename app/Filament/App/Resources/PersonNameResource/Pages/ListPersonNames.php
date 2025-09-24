<?php

namespace App\Filament\App\Resources\PersonNameResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonNames extends ListRecords
{
    protected static string $resource = PersonNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
