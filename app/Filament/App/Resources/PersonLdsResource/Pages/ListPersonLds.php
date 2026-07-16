<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonLdsResource\Pages;

use App\Filament\App\Resources\PersonLdsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonLds extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonLdsResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
