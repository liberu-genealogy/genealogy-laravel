<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRepoResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SourceRepoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceRepos extends ListRecords
{
    #[\Override]
    protected static string $resource = SourceRepoResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
