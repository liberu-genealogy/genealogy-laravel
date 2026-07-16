<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameResource\Pages;

use App\Filament\App\Resources\PersonNameResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonNames extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonNameResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
