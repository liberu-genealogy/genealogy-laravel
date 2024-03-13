&lt;?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Livewire;

class DescendantChartPage extends Page
{
    protected static string $view = 'filament.pages.descendant-chart';
    protected static ?string $resource = null;
    protected static ?string $title = 'Descendant Chart';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function getTitle(): string
    {
        return static::$title;
    }

    /**
     * Retrieves the navigation icon for the Descendant Chart page.
     *
     * @return string The navigation icon.
     */
    public function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    public function mount(): void
    {
        Livewire::mount(\App\Http\Livewire\DescendantChartComponent::class);
    }
}
