<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PublicationResource\Pages;

use App\Filament\App\Resources\PublicationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPublication extends EditRecord
{
    #[\Override]
    protected static string $resource = PublicationResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
