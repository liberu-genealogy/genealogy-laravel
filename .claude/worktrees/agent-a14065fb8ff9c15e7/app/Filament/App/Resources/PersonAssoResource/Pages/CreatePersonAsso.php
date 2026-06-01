<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAssoResource\Pages;

use App\Filament\App\Resources\PersonAssoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonAsso extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonAssoResource::class;
}
