<?php

namespace App\Filament\App\Resources\PersonSubmResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonSubmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonSubms extends ListRecords
{
    protected static string $resource = PersonSubmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
