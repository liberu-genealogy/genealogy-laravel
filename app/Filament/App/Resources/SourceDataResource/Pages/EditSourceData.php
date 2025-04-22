<?php

namespace App\Filament\App\Resources\SourceDataResource\Pages;

use App\Filament\App\Resources\SourceDataResource;
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
