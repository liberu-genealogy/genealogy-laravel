<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\MenuResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\MenuResource;

class ListMenus extends ListRecords
{
    #[\Override]
    protected static string $resource = MenuResource::class;
}
