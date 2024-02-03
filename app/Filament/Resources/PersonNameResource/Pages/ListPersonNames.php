<?php

namespace App\Filament\Resources\PersonNameResource\Pages;

use App\Filament\Resources\PersonNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonNames extends ListRecords
{
    protected static string $resource = PersonNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
