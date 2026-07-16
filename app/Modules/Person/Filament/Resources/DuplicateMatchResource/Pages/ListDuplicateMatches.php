<?php

declare(strict_types=1);

namespace App\Modules\Person\Filament\Resources\DuplicateMatchResource\Pages;

use App\Modules\Person\Filament\Resources\DuplicateMatchResource;
use Filament\Resources\Pages\ListRecords;

class ListDuplicateMatches extends ListRecords
{
    #[\Override]
    protected static string $resource = DuplicateMatchResource::class;
}
