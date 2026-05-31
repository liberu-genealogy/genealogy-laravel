<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\MediaObjectResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\MediaObjectResource;
use Filament\Actions;
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
