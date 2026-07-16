<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SubmResource\Pages;

use App\Filament\App\Resources\SubmResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubm extends EditRecord
{
    #[\Override]
    protected static string $resource = SubmResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
