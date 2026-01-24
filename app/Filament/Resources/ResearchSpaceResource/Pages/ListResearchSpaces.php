<?php

namespace App\Filament\Resources\ResearchSpaceResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ResearchSpaceResource;

class ListResearchSpaces extends ListRecords
{
    protected static string $resource = ResearchSpaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
