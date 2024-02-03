<?php

namespace App\Filament\Resources\PersonNameResource\Pages;

use App\Filament\Resources\PersonNameResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonName extends CreateRecord
{
    protected static string $resource = PersonNameResource::class;
}
