<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilyResource\Pages;

use App\Filament\App\Resources\FamilyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFamily extends EditRecord
{
    #[\Override]
    protected static string $resource = FamilyResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
