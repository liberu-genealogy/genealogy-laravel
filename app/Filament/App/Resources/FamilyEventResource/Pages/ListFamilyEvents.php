<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilyEventResource\Pages;

use App\Filament\App\Resources\FamilyEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFamilyEvents extends ListRecords
{
    #[\Override]
    protected static string $resource = FamilyEventResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
