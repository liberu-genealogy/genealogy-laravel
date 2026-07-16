<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\AddrResource\Pages;

use App\Filament\App\Resources\AddrResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAddrs extends ListRecords
{
    #[\Override]
    protected static string $resource = AddrResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
