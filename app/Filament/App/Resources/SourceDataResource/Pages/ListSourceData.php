<?php

namespace App\Filament\App\Resources\SourceDataResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SourceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceData extends ListRecords
{
    protected static string $resource = SourceDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
