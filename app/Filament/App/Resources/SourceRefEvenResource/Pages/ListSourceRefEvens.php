<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRefEvenResource\Pages;

use App\Filament\App\Resources\SourceRefEvenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSourceRefEvens extends ListRecords
{
    #[\Override]
    protected static string $resource = SourceRefEvenResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
