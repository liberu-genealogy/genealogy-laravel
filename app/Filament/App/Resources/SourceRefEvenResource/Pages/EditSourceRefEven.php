<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SourceRefEvenResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SourceRefEvenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceRefEven extends EditRecord
{
    #[\Override]
    protected static string $resource = SourceRefEvenResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
