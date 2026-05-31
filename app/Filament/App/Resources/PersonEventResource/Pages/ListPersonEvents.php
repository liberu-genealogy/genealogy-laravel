<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonEventResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonEvents extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonEventResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
