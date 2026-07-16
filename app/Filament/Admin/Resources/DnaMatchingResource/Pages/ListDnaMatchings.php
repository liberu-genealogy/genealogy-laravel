<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\DnaMatchingResource\Pages;

use App\Filament\Admin\Resources\DnaMatchingResource;
use Filament\Resources\Pages\ListRecords;

class ListDnaMatchings extends ListRecords
{
    #[\Override]
    protected static string $resource = DnaMatchingResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [];
    }
}
