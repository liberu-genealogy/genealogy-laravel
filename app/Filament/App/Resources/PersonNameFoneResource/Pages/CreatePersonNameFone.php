<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameFoneResource\Pages;

use App\Filament\App\Resources\PersonNameFoneResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonNameFone extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonNameFoneResource::class;
}
