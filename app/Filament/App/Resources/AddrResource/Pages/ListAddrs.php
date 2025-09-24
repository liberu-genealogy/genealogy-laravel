<?php

namespace App\Filament\App\Resources\AddrResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\AddrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddrs extends ListRecords
{
    protected static string $resource = AddrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
