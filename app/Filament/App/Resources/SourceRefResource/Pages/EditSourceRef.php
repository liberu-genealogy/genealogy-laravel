<?php

namespace App\Filament\App\Resources\SourceRefResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SourceRefResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceRef extends EditRecord
{
    protected static string $resource = SourceRefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
