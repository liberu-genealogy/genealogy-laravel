<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ChanResource\Pages;

use App\Filament\App\Resources\ChanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChan extends EditRecord
{
    #[\Override]
    protected static string $resource = ChanResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
