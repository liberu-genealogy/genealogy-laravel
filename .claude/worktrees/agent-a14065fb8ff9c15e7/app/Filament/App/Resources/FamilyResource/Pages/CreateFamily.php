<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilyResource\Pages;

use App\Filament\App\Resources\FamilyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFamily extends CreateRecord
{
    #[\Override]
    protected static string $resource = FamilyResource::class;
}
