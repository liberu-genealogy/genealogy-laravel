<?php

namespace App\Filament\App\Resources\CitationResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\CitationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCitation extends EditRecord
{
    protected static string $resource = CitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
