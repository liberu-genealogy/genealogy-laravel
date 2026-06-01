<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonAnciResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonAnciResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonAnci extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonAnciResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
