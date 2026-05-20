<?php

namespace App\Filament\App\Resources\SourceResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSource extends EditRecord
{
    protected static string $resource = SourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
