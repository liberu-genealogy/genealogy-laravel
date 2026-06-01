<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameResource\Pages;

use App\Filament\App\Resources\PersonNameResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonName extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonNameResource::class;
}
