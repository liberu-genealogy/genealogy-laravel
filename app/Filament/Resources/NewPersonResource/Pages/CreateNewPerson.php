<?php

namespace App\Filament\Resources\NewPersonResource\Pages;

use App\Filament\Resources\NewPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewPerson extends CreateRecord
{
    protected static string $resource = NewPersonResource::class;
}
