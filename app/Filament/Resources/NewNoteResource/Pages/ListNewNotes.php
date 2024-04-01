<?php

namespace App\Filament\Resources\NewNoteResource\Pages;

use App\Filament\Resources\NewNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewNotes extends ListRecords
{
    protected static string $resource = NewNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
