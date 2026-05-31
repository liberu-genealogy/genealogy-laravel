<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DatabaseResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\DatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatabases extends ListRecords
{
    #[\Override]
    protected static string $resource = DatabaseResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
