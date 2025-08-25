<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class DescendantChartPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    
    protected static ?string $navigationLabel = 'Descendant Chart';
    
    protected static ?string $navigationGroup = 'Charts & Reports';
    
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.app.pages.descendant-chart-page';

    protected static ?string $title = 'Descendant Chart';

    public function getWidgets(): array
    {
        return [
            \App\Http\Livewire\DescendantChartWidget::class,
        ];
    }
}