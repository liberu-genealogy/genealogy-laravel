<?php

namespace App\Filament\App\Resources\PublicationResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PublicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublication extends EditRecord
{
    protected static string $resource = PublicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
