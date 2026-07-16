<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonSubmResource\Pages;

use App\Filament\App\Resources\PersonSubmResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonSubm extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonSubmResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
