<?php

namespace App\Filament\Resources\FamilySlgsResource\Pages;

use App\Filament\Resources\FamilySlgsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilySlgs extends ListRecords
{
    protected static string $resource = FamilySlgsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
