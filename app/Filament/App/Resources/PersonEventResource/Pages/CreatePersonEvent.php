<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonEventResource\Pages;

use App\Filament\App\Resources\PersonEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonEvent extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonEventResource::class;
}
