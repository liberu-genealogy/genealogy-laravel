<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DnaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\DnaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDnas extends ListRecords
{
    #[\Override]
    protected static string $resource = DnaResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Upload'),
        ];
    }
}
