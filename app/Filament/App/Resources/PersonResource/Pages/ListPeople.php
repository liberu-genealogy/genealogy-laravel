<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeople extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
