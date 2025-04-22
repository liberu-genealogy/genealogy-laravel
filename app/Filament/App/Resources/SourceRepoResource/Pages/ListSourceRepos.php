<?php

namespace App\Filament\App\Resources\SourceRepoResource\Pages;

use App\Filament\App\Resources\SourceRepoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceRepos extends ListRecords
{
    protected static string $resource = SourceRepoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
