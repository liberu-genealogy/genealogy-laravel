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
        return $this->title;
    }

    public static function getNavigationIcon(): string
    {
        return $this->navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\DescendantChartComponent::class);
    }
}
/**
 * Defines the Descendant Chart page in the Filament admin panel.
 */
        Livewire::mount(\App\Http\Livewire\DescendantChartComponent::class);
    }
}
