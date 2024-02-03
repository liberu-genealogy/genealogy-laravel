<?php

namespace App\Filament\Resources\SourceRefEvenResource\Pages;

use App\Filament\Resources\SourceRefEvenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceRefEvens extends ListRecords
{
    protected static string $resource = SourceRefEvenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
