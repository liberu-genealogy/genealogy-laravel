<?php

namespace App\Filament\App\Resources\SourceResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSources extends ListRecords
{
    protected static string $resource = SourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
