<?php

namespace App\Filament\App\Resources\SubmResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SubmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubms extends ListRecords
{
    protected static string $resource = SubmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
