<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DatabaseResource\Pages;

use App\Filament\App\Resources\DatabaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDatabase extends CreateRecord
{
    #[\Override]
    protected static string $resource = DatabaseResource::class;
}
