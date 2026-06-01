<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CitationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\CitationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCitations extends ListRecords
{
    #[\Override]
    protected static string $resource = CitationResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
