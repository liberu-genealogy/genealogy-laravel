<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonResource\Pages;

use App\Filament\App\Resources\PersonResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePerson extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonResource::class;
}
