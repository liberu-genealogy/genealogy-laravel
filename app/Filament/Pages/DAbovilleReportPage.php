<?php

namespace App\Filament\Pages;

/**
 * Defines the DAbovilleReportPage class, extending CustomFilamentBasePage.
 * This class is responsible for handling the rendering of the DAboville report within the genealogy application,
 * leveraging Livewire for dynamic content management. It utilizes a specific Livewire component defined by the $view property.
 */

use App\Filament\Pages\CustomFilamentBasePage;
use Livewire\Livewire;

class DAbovilleReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.daboville-report';

//    public function render(): \Illuminate\Contracts\Support\Renderable
//    {
//        return \Livewire::mount(static::$view);
//    }

    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
}
