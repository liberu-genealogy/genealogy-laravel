<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ChanResource\Pages;

use App\Filament\App\Resources\ChanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChan extends CreateRecord
{
    #[\Override]
    protected static string $resource = ChanResource::class;
}
