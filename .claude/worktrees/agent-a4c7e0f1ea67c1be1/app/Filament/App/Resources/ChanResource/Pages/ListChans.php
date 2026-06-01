<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ChanResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\ChanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChans extends ListRecords
{
    #[\Override]
    protected static string $resource = ChanResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
