<?php

namespace App\Filament\Resources\NewPersonAliaResource\Pages;

use App\Filament\Resources\NewPersonAliaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewPersonAlia extends EditRecord
{
    protected static string $resource = NewPersonAliaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
