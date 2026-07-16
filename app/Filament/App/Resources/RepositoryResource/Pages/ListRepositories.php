<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RepositoryResource\Pages;

use App\Filament\App\Resources\RepositoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRepositories extends ListRecords
{
    #[\Override]
    protected static string $resource = RepositoryResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
