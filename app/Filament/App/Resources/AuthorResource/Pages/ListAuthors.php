<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\AuthorResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuthors extends ListRecords
{
    #[\Override]
    protected static string $resource = AuthorResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
