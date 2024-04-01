<?php

namespace App\Filament\Resources\NewCitationResource\Pages;

use App\Filament\Resources\NewCitationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewCitations extends ListRecords
{
    protected static string $resource = NewCitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
