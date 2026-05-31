<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonLdsResource\Pages;

use App\Filament\App\Resources\PersonLdsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonLds extends CreateRecord
{
    #[\Override]
    protected static string $resource = PersonLdsResource::class;
}
