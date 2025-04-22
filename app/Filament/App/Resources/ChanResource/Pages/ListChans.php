<?php

namespace App\Filament\App\Resources\ChanResource\Pages;

use App\Filament\App\Resources\ChanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChans extends ListRecords
{
    protected static string $resource = ChanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
