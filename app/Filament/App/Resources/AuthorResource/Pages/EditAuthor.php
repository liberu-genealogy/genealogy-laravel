<?php

namespace App\Filament\App\Resources\AuthorResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuthor extends EditRecord
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
