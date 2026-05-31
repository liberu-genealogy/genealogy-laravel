<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SmartMatchResource\Pages;

use App\Filament\App\Resources\SmartMatchResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSmartMatch extends ViewRecord
{
    #[\Override]
    protected static string $resource = SmartMatchResource::class;
}
