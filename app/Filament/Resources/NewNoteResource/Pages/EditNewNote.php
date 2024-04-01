<?php

namespace App\Filament\Resources\NewNoteResource\Pages;

use App\Filament\Resources\NewNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewNote extends EditRecord
{
    protected static string $resource = NewNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
