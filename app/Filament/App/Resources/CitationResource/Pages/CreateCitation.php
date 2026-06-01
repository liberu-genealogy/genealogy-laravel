<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CitationResource\Pages;

use App\Filament\App\Resources\CitationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCitation extends CreateRecord
{
    #[\Override]
    protected static string $resource = CitationResource::class;
}
