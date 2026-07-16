<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceResource\Pages;

use App\Filament\App\Resources\SourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSources extends ListRecords
{
    #[\Override]
    protected static string $resource = SourceResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
