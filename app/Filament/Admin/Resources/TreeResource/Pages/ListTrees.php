<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\TreeResource\Pages;

use App\Filament\Admin\Resources\TreeResource;
use Filament\Resources\Pages\ListRecords;

class ListTrees extends ListRecords
{
    #[\Override]
    protected static string $resource = TreeResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [];
    }
}
