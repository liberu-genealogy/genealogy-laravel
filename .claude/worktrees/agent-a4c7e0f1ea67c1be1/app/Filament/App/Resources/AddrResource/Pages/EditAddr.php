<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\AddrResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\AddrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddr extends EditRecord
{
    #[\Override]
    protected static string $resource = AddrResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
