<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RefnResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\RefnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefns extends ListRecords
{
    #[\Override]
    protected static string $resource = RefnResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
