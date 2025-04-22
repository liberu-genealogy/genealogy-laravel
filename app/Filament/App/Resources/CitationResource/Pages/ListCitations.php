<?php

namespace App\Filament\App\Resources\CitationResource\Pages;

use App\Filament\App\Resources\CitationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCitations extends ListRecords
{
    protected static string $resource = CitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
