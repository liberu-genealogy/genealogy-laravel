<?php

namespace App\Filament\App\Resources\PersonLdsResource\Pages;

use App\Filament\App\Resources\PersonLdsResource;
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
