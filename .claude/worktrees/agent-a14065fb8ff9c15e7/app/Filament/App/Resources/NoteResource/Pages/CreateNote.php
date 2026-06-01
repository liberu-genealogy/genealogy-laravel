<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\NoteResource\Pages;

use App\Filament\App\Resources\NoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    #[\Override]
    protected static string $resource = NoteResource::class;
}
