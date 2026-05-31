<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAnciResource\Pages;

use App\Filament\App\Resources\PersonAnciResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonAnci extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonAnciResource::class;
}
