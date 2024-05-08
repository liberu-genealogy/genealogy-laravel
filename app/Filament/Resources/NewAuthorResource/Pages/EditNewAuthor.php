<?php

namespace App\Filament\Resources\NewAuthorResource\Pages;

use App\Filament\Resources\NewAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewAuthor extends EditRecord
{
    protected static string $resource = NewAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
