<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DatabaseResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\DatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatabase extends EditRecord
{
    #[\Override]
    protected static string $resource = DatabaseResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
