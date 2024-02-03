<?php

namespace App\Filament\Resources\PersonEventResource\Pages;

use App\Filament\Resources\PersonEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonEvents extends ListRecords
{
    protected static string $resource = PersonEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
