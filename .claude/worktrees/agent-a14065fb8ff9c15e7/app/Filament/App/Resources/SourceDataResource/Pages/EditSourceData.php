<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceDataResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SourceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceData extends EditRecord
{
    #[\Override]
    protected static string $resource = SourceDataResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
