<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FamilySlgsResource\Pages;

use App\Filament\App\Resources\FamilySlgsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFamilySlgs extends CreateRecord
{
    #[\Override]
    protected static string $resource = FamilySlgsResource::class;
}
