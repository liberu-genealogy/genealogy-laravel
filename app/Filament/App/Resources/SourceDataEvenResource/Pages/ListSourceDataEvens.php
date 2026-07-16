<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceDataEvenResource\Pages;

use App\Filament\App\Resources\SourceDataEvenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSourceDataEvens extends ListRecords
{
    #[\Override]
    protected static string $resource = SourceDataEvenResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
