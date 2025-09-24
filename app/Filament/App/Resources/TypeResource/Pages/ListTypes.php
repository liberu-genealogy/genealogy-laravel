<?php

namespace App\Filament\App\Resources\TypeResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\TypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypes extends ListRecords
{
    protected static string $resource = TypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
