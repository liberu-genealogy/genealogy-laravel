<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RefnResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\RefnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefn extends EditRecord
{
    #[\Override]
    protected static string $resource = RefnResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
