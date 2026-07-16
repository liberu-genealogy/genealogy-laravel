<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\TypeResource\Pages;

use App\Filament\App\Resources\TypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTypes extends ListRecords
{
    #[\Override]
    protected static string $resource = TypeResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
