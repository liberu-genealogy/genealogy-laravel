<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAssoResource\Pages;

use App\Filament\App\Resources\PersonAssoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonAsso extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonAssoResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
