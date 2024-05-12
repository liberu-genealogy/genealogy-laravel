<?php

namespace App\Filament\Widgets;

use App\Http\Livewire\DabovilleReport;
use App\Http\Livewire\HenryReport;
use App\Http\Livewire\AhnentafelReport;
use Filament\Widgets\Widget;

class ReportWidget extends Widget
{
    protected static string $view = 'filament.widgets.report-widget';

    protected function getHeading(): string
    {
        return 'Reports';
    }

    public function mount(): void
    {
        $this->livewire = [
            'daboville-report' => DabovilleReport::class,
            'henry-report' => HenryReport::class,
            'ahnentafel-report' => AhnentafelReport::class,
        ];
    }

    protected function getViewData(): array
    {
        return [
            'livewire' => $this->livewire,
        ];
    }
}