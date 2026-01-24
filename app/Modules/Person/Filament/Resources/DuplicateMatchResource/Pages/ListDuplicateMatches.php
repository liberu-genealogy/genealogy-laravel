<?php

namespace App\Modules\Person\Filament\Resources\DuplicateMatchResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Modules\Person\Filament\Resources\DuplicateMatchResource;

class ListDuplicateMatches extends ListRecords
{
    protected static string $resource = DuplicateMatchResource::class;
}
