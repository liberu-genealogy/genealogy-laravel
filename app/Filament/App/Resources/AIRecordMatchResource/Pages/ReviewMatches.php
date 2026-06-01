<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\AIRecordMatchResource\Pages;

use App\Filament\App\Resources\AIRecordMatchResource;
use Filament\Resources\Pages\ListRecords;

class ReviewMatches extends ListRecords
{
    #[\Override]
    protected static string $resource = AIRecordMatchResource::class;
}
