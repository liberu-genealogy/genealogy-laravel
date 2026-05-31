<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\MenuResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\MenuResource;

class CreateMenu extends CreateRecord
{
    #[\Override]
    protected static string $resource = MenuResource::class;
}
