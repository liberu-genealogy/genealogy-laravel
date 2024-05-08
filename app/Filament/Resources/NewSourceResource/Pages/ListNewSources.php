<?php

namespace App\Filament\Resources\NewSourceResource\Pages;

use App\Filament\Resources\NewSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewSources extends ListRecords
{
    protected static string $resource = NewSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
