<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRepoResource\Pages;

use App\Filament\App\Resources\SourceRepoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSourceRepo extends CreateRecord
{
    #[\Override]
    protected static string $resource = SourceRepoResource::class;
}
