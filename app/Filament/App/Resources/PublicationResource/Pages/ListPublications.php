<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PublicationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PublicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublications extends ListRecords
{
    #[\Override]
    protected static string $resource = PublicationResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
