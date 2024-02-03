<?php

namespace App\Filament\Resources\SourceDataResource\Pages;

use App\Filament\Resources\SourceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceData extends ListRecords
{
    protected static string $resource = SourceDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
