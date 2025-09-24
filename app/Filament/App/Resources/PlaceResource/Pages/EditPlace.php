<?php

namespace App\Filament\App\Resources\PlaceResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PlaceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlace extends EditRecord
{
    protected static string $resource = PlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
