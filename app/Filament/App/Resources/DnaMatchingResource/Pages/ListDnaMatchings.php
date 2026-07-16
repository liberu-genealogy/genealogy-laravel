<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DnaMatchingResource\Pages;

use App\Filament\App\Resources\DnaMatchingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDnaMatchings extends ListRecords
{
    #[\Override]
    protected static string $resource = DnaMatchingResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
