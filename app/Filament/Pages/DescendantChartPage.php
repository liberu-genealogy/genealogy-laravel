<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class DescendantChartPage extends Page
{
    protected static string $view = 'filament.pages.descendant-chart';
    protected static ?string $resource = null;
    protected static ?string $title = 'Descendant Chart';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\DescendantChartComponent::class);
    }
}
