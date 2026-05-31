<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameRomnResource\Pages;

use App\Filament\App\Resources\PersonNameRomnResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonNameRomn extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonNameRomnResource::class;
}
