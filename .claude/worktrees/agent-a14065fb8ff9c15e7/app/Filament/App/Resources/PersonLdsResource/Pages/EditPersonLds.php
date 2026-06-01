<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonLdsResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonLdsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonLds extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonLdsResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
