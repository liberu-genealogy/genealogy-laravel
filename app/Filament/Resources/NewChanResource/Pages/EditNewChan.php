<?php

namespace App\Filament\Resources\NewChanResource\Pages;

use App\Filament\Resources\NewChanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewChan extends EditRecord
{
    protected static string $resource = NewChanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
