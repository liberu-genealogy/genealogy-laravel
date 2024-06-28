<?php

namespace App\Filament\Resources\AddrResource\Pages;

use App\Filament\Resources\AddrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddrs extends ListRecords
{
    protected static string $resource = AddrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
