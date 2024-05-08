<?php

namespace App\Filament\Resources\NewAddrResource\Pages;

use App\Filament\Resources\NewAddrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewAddrs extends ListRecords
{
    protected static string $resource = NewAddrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
