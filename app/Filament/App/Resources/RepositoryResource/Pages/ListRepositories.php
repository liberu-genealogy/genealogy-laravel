<?php

namespace App\Filament\App\Resources\RepositoryResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\RepositoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepositories extends ListRecords
{
    protected static string $resource = RepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
