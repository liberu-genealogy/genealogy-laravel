<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRefResource\Pages;

use App\Filament\App\Resources\SourceRefResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSourceRef extends EditRecord
{
    #[\Override]
    protected static string $resource = SourceRefResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
