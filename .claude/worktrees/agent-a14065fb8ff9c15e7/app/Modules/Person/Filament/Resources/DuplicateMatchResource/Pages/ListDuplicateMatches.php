<?php

declare(strict_types=1);

namespace App\Modules\Person\Filament\Resources\DuplicateMatchResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Modules\Person\Filament\Resources\DuplicateMatchResource;

class ListDuplicateMatches extends ListRecords
{
    #[\Override]
    protected static string $resource = DuplicateMatchResource::class;
}
