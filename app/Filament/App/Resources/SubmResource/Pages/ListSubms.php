<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SubmResource\Pages;

use App\Filament\App\Resources\SubmResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubms extends ListRecords
{
    #[\Override]
    protected static string $resource = SubmResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
