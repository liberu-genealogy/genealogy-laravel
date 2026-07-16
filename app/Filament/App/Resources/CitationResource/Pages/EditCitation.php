<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CitationResource\Pages;

use App\Filament\App\Resources\CitationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCitation extends EditRecord
{
    #[\Override]
    protected static string $resource = CitationResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
