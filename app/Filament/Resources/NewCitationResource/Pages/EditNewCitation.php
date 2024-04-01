<?php

namespace App\Filament\Resources\NewCitationResource\Pages;

use App\Filament\Resources\NewCitationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewCitation extends EditRecord
{
    protected static string $resource = NewCitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
