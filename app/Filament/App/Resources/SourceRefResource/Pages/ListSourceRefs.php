<?php

namespace App\Filament\App\Resources\SourceRefResource\Pages;

use App\Filament\App\Resources\SourceRefResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceRefs extends ListRecords
{
    protected static string $resource = SourceRefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
