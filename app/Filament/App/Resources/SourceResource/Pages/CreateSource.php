<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceResource\Pages;

use App\Filament\App\Resources\SourceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSource extends CreateRecord
{
    #[\Override]
    protected static string $resource = SourceResource::class;
}
