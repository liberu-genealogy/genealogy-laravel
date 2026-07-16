<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SubnResource\Pages;

use App\Filament\App\Resources\SubnResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubn extends EditRecord
{
    #[\Override]
    protected static string $resource = SubnResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
