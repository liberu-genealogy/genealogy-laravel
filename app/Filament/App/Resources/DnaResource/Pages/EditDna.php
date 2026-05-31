<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\DnaResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\DnaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDna extends EditRecord
{
    #[\Override]
    protected static string $resource = DnaResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
