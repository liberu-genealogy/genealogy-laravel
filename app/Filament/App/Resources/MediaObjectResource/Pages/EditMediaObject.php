<?php

namespace App\Filament\App\Resources\MediaObjectResource\Pages;

use App\Filament\App\Resources\MediaObjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMediaObject extends EditRecord
{
    protected static string $resource = MediaObjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
