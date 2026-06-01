<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAnciResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\PersonAnciResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonAncis extends ListRecords
{
    #[\Override]
    protected static string $resource = PersonAnciResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
