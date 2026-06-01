<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilyResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\FamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilies extends ListRecords
{
    #[\Override]
    protected static string $resource = FamilyResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
