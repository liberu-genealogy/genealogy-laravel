<?php

namespace App\Modules\Places\Filament\Resources\PlaceResource\Pages;

use App\Modules\Places\Filament\Resources\PlaceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlace extends CreateRecord
{
    protected static string $resource = PlaceResource::class;
}