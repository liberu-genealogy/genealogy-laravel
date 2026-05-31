<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RecordTypeResource\Pages;

use App\Filament\App\Resources\RecordTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecordType extends EditRecord
{
    #[\Override]
    protected static string $resource = RecordTypeResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
