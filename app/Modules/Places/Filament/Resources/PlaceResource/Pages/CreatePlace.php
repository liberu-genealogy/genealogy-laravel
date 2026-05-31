<?php

declare(strict_types=1);

namespace App\Modules\Places\Filament\Resources\PlaceResource\Pages;

use App\Modules\Places\Filament\Resources\PlaceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlace extends CreateRecord
{
    #[\Override]
    protected static string $resource = PlaceResource::class;
}
