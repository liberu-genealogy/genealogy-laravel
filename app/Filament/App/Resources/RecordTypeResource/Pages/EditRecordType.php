<?php

namespace App\Filament\App\Resources\RecordTypeResource\Pages;

use App\Filament\App\Resources\RecordTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecordType extends EditRecord
{
    protected static string $resource = RecordTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
