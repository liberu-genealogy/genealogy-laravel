<?php

namespace App\Filament\Resources\NewAuthorResource\Pages;

use App\Filament\Resources\NewAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewAuthor extends CreateRecord
{
    protected static string $resource = NewAuthorResource::class;
}
