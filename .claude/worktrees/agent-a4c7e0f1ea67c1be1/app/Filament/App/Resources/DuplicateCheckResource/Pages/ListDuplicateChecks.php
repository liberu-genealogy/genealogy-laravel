<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DuplicateCheckResource\Pages;

use App\Filament\App\Resources\DuplicateCheckResource;
use Filament\Resources\Pages\ListRecords;

class ListDuplicateChecks extends ListRecords
{
    #[\Override]
    protected static string $resource = DuplicateCheckResource::class;
}
