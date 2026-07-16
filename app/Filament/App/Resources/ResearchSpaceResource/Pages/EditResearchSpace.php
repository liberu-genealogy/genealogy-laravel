<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ResearchSpaceResource\Pages;

use App\Filament\App\Resources\ResearchSpaceResource;
use Filament\Resources\Pages\EditRecord;

class EditResearchSpace extends EditRecord
{
    #[\Override]
    protected static string $resource = ResearchSpaceResource::class;
}
