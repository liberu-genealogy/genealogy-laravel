<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameFoneResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonNameFoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonNameFone extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonNameFoneResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
