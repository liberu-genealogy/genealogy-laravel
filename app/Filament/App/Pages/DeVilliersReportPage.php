<?php

namespace App\Filament\App\Pages;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\MediaObjectResource;
use App\Filament\App\Resources\NoteResource;
use App\Filament\App\Resources\PublicationResource;
use App\Filament\App\Resources\RepositoryResource;
use App\Filament\App\Resources\SourceDataResource;
use App\Filament\App\Resources\TypeResource;
use Filament\Pages\Page;

class DeVilliersReportPage extends Page
{
    protected string $view = 'de-villiers-report-page';

    protected static ?string $title = 'Devilliers Report';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'De Villiers Report';
    protected static string | \UnitEnum | null $navigationGroup = '📄 Reports';
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
