<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAliaResource\Pages;

use App\Filament\App\Resources\PersonAliaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonAlia extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonAliaResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
