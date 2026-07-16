<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAssoResource\Pages;

use App\Filament\App\Resources\PersonAssoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonAssos extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonAssoResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
