<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DnaMatchingResource\Pages;

use App\Filament\App\Resources\DnaMatchingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDnaMatching extends CreateRecord
{
    #[\Override]
    protected static string $resource = DnaMatchingResource::class;
}
