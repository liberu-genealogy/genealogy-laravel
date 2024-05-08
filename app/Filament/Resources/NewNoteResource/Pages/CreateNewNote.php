<?php

namespace App\Filament\Resources\NewNoteResource\Pages;

use App\Filament\Resources\NewNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewNote extends CreateRecord
{
    protected static string $resource = NewNoteResource::class;
}
