<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAliaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonAliaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonAlias extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonAliaResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
