<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAliaResource\Pages;

use App\Filament\App\Resources\PersonAliaResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonAlia extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonAliaResource::class;
}
