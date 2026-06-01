<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\MenuResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\MenuResource;

class EditMenu extends EditRecord
{
    #[\Override]
    protected static string $resource = MenuResource::class;
}
