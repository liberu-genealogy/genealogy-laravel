<?php

namespace App\Filament\Resources\NewPersonResource\Pages;

use App\Filament\Resources\NewPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewPeople extends ListRecords
{
    protected static string $resource = NewPersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
