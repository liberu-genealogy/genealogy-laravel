<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\GedcomResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\App\Resources\GedcomResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGedcom extends ViewRecord
{
    #[\Override]
    protected static string $resource = GedcomResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
