<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceDataEvenResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SourceDataEvenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceDataEven extends EditRecord
{
    #[\Override]
    protected static string $resource = SourceDataEvenResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
