<?php

namespace App\Filament\App\Resources\SubnResource\Pages;

use App\Filament\App\Resources\SubnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubns extends ListRecords
{
    protected static string $resource = SubnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
