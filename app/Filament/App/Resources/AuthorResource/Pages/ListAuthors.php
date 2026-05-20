<?php

namespace App\Filament\App\Resources\AuthorResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuthors extends ListRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
