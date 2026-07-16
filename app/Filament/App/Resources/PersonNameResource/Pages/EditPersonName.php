<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameResource\Pages;

use App\Filament\App\Resources\PersonNameResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonName extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonNameResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
