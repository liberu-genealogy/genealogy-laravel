<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSource extends EditRecord
{
    #[\Override]
    protected static string $resource = SourceResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
