<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\MediaObjectResource\Pages;

use App\Filament\App\Resources\MediaObjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMediaObject extends EditRecord
{
    #[\Override]
    protected static string $resource = MediaObjectResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
