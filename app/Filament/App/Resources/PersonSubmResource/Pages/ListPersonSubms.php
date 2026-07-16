<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonSubmResource\Pages;

use App\Filament\App\Resources\PersonSubmResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonSubms extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonSubmResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
