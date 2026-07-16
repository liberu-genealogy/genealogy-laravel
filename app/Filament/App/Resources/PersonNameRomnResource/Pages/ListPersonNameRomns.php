<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameRomnResource\Pages;

use App\Filament\App\Resources\PersonNameRomnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonNameRomns extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonNameRomnResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
