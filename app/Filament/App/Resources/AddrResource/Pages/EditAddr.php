<?php

namespace App\Filament\App\Resources\AddrResource\Pages;

use App\Filament\App\Resources\AddrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddr extends EditRecord
{
    protected static string $resource = AddrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
