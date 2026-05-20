<?php

namespace App\Filament\App\Resources\NoteResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
