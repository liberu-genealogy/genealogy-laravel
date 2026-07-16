<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRefResource\Pages;

use App\Filament\App\Resources\SourceRefResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSourceRefs extends ListRecords
{
    #[\Override]
    protected static string $resource = SourceRefResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
