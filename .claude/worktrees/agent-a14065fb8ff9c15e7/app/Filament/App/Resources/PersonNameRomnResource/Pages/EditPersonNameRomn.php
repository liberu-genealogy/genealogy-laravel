<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\PersonNameRomnResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\PersonNameRomnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonNameRomn extends EditRecord
{
    #[\Override]
    protected static string $resource = PersonNameRomnResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
