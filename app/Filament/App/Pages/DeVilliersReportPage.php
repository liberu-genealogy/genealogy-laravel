<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Filament\App\Resources\MediaObjectResource;
use App\Filament\App\Resources\NoteResource;
use App\Filament\App\Resources\PublicationResource;
use App\Filament\App\Resources\RepositoryResource;
use App\Filament\App\Resources\SourceDataResource;
use App\Filament\App\Resources\TypeResource;
use Filament\Pages\Page;

class DeVilliersReportPage extends Page
{
    #[\Override]
    protected string $view = 'de-villiers-report-page';

    #[\Override]
    protected static ?string $title = 'Devilliers Report';

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    #[\Override]
    protected static ?string $navigationLabel = 'De Villiers Report';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📊 Charts & Reports';
    // {
    //     Livewire::mount('DeVilliersReportWidget');
    // }

    public function resources(): array
    {
        return [
            TypeResource::class,
            MediaObjectResource::class,
            NoteResource::class,
            RepositoryResource::class,
            PublicationResource::class,
            SourceDataResource::class,
        ];
    }
}
