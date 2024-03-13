<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HenryReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.henry-report';
    protected static ?string $title = 'Henry Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    /**
     * This function returns the title of the page.
     *
     * @return string The title of the page.
     */
    public function getTitle(): string
    {
        return static::$title;
    }

    /**
     * This function returns the navigation icon of the page.
     *
     * @return string The navigation icon of the page.
     */
    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    /**
     * This function renders the page.
     *
     * @return \Illuminate\Contracts\Support\Renderable The rendered page.
     */
    public function render(): \Illuminate\Contracts\Support\Renderable
    {
        return \Livewire::mount(static::$view);
    }

    /**
     * This function is called when the page is mounted.
     *
     * @return void
     */
    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
}
    /**
     * This function returns the title of the page.
     *
     * @return string The title of the page.
     */
    public function getTitle(): string
    {
        return static::$title;
    }

    /**
     * This function returns the navigation icon of the page.
     *
     * @return string The navigation icon of the page.
     */
    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

    /**
     * This function renders the page.
     *
     * @return \Illuminate\Contracts\Support\Renderable The rendered page.
     */
    public function render(): \Illuminate\Contracts\Support\Renderable
    {
        return \Livewire::mount(static::$view);
    }

    /**
     * This function is called when the page is mounted.
     *
     * @return void
     */
    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
