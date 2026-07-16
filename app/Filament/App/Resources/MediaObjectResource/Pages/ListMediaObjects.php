<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\MediaObjectResource\Pages;

use App\Filament\App\Resources\MediaObjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMediaObjects extends ListRecords
{
    #[\Override]
    protected static string $resource = MediaObjectResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
