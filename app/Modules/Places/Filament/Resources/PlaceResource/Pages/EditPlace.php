<?php

namespace App\Modules\Places\Filament\Resources\PlaceResource\Pages;

use Filament\Actions\DeleteAction;
use App\Modules\Places\Filament\Resources\PlaceResource;
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