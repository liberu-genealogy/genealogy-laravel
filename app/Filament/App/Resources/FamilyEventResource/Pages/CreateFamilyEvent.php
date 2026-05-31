<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilyEventResource\Pages;

use App\Filament\App\Resources\FamilyEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFamilyEvent extends CreateRecord
{
    #[\Override]
    protected static string $resource = FamilyEventResource::class;
}
