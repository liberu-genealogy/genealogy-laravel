<?php

namespace App\Filament\App\Resources\PersonEventResource\Pages;

use App\Filament\App\Resources\PersonEventResource;
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
