<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceDataResource\Pages;

use App\Filament\App\Resources\SourceDataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSourceData extends ListRecords
{
    #[\Override]
    protected static string $resource = SourceDataResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
