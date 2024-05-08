<?php

namespace App\Filament\Resources\NewAuthorResource\Pages;

use App\Filament\Resources\NewAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewAuthors extends ListRecords
{
    protected static string $resource = NewAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
