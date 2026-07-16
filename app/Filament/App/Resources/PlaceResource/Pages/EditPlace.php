<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PlaceResource\Pages;

use App\Filament\App\Resources\PlaceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlace extends EditRecord
{
    #[\Override]
    protected static string $resource = PlaceResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
