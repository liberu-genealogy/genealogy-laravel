<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRepoResource\Pages;

use App\Filament\App\Resources\SourceRepoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSourceRepo extends EditRecord
{
    #[\Override]
    protected static string $resource = SourceRepoResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
