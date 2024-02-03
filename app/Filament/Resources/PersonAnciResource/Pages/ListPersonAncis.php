<?php

namespace App\Filament\Resources\PersonAnciResource\Pages;

use App\Filament\Resources\PersonAnciResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonAncis extends ListRecords
{
    protected static string $resource = PersonAnciResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
