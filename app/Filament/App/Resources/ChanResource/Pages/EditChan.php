<?php

namespace App\Filament\App\Resources\ChanResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\ChanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChan extends EditRecord
{
    protected static string $resource = ChanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
