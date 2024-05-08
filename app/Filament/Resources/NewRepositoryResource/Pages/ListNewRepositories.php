<?php

namespace App\Filament\Resources\NewRepositoryResource\Pages;

use App\Filament\Resources\NewRepositoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewRepositories extends ListRecords
{
    protected static string $resource = NewRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
