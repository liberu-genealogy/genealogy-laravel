<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilySlgsResource\Pages;

use App\Filament\App\Resources\FamilySlgsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFamilySlgs extends ListRecords
{
    #[\Override]
    protected static string $resource = FamilySlgsResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
