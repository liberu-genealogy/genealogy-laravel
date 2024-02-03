<?php

namespace App\Filament\Resources\SubmResource\Pages;

use App\Filament\Resources\SubmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubms extends ListRecords
{
    protected static string $resource = SubmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
