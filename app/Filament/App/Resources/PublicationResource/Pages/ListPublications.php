<?php

namespace App\Filament\App\Resources\PublicationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PublicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublications extends ListRecords
{
    protected static string $resource = PublicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
