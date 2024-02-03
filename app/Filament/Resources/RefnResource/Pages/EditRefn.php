<?php

namespace App\Filament\Resources\RefnResource\Pages;

use App\Filament\Resources\RefnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefn extends EditRecord
{
    protected static string $resource = RefnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
