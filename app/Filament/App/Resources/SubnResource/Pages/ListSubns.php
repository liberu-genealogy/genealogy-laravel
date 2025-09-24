<?php

namespace App\Filament\App\Resources\SubnResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SubnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubns extends ListRecords
{
    protected static string $resource = SubnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
