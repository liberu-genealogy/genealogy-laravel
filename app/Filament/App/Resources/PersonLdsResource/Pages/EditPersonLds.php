<?php

namespace App\Filament\Resources\PersonLdsResource\Pages;

use App\Filament\Resources\PersonLdsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonLds extends EditRecord
{
    protected static string $resource = PersonLdsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
