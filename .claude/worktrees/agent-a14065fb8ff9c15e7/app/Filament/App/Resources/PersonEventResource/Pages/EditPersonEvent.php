<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonEventResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonEvent extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonEventResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
