<?php

namespace App\Filament\Resources\SourceDataResource\Pages;

use App\Filament\Resources\SourceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceData extends EditRecord
{
    protected static string $resource = SourceDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
