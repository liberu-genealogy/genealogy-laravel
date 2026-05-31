<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonSubmResource\Pages;

use App\Filament\App\Resources\PersonSubmResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonSubm extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonSubmResource::class;
}
