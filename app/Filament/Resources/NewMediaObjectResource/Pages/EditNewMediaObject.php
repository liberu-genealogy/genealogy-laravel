<?php

namespace App\Filament\Resources\NewMediaObjectResource\Pages;

use App\Filament\Resources\NewMediaObjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewMediaObject extends EditRecord
{
    protected static string $resource = NewMediaObjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
