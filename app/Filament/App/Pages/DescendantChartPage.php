<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use UnitEnum;
use BackedEnum;
class DescendantChartPage extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationLabel = 'Descendant Chart';

    protected static string | UnitEnum | null $navigationGroup = 'Charts & Reports';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.app.pages.descendant-chart-page';

    protected static ?string $title = 'Descendant Chart';

    public function getWidgets(): array
    {
        return [
            \App\Http\Livewire\DescendantChartWidget::class,
        ];
    }
}
