<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ResearchSpaceResource\Pages;

use App\Filament\App\Resources\ResearchSpaceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResearchSpaces extends ListRecords
{
    #[\Override]
    protected static string $resource = ResearchSpaceResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
