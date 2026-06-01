<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\AddrResource\Pages;

use App\Filament\App\Resources\AddrResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAddr extends CreateRecord
{
    #[\Override]
    protected static string $resource = AddrResource::class;
}
