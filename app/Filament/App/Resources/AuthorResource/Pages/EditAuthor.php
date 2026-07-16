<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\AuthorResource\Pages;

use App\Filament\App\Resources\AuthorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthor extends EditRecord
{
    #[\Override]
    protected static string $resource = AuthorResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
