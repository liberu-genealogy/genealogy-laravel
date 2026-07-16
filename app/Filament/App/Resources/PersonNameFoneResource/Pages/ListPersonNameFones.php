<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameFoneResource\Pages;

use App\Filament\App\Resources\PersonNameFoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonNameFones extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonNameFoneResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
