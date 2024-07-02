<?php

namespace App\Filament\Resources\RefnResource\Pages;

use App\Filament\Resources\RefnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefns extends ListRecords
{
    protected static string $resource = RefnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
