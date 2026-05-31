<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceDataResource\Pages;

use App\Filament\App\Resources\SourceDataResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSourceData extends CreateRecord
{
    #[\Override]
    protected static string $resource = SourceDataResource::class;
}
