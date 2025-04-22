<?php

namespace App\Filament\App\Resources\RefnResource\Pages;

use App\Filament\App\Resources\RefnResource;
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
