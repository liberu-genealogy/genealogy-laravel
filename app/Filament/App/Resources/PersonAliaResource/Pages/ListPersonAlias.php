<?php

namespace App\Filament\App\Resources\PersonAliaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonAliaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonAlias extends ListRecords
{
    protected static string $resource = PersonAliaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
