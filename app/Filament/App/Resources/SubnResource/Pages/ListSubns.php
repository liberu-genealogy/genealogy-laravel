<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SubnResource\Pages;

use App\Filament\App\Resources\SubnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubns extends ListRecords
{
    #[\Override]
    protected static string $resource = SubnResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
