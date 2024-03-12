<?php
/**
 * Page component for generating the d'Aboville Report within the genealogy application.
 * This page utilizes Livewire for dynamic data handling.
 */

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DAbovilleReportPage extends Page
{
    protected static string $view = 'livewire.daboville-report';

/**
 * Renders the d'Aboville Report page view.
 *
 * @return \Illuminate\Contracts\View\View The view instance for the d'Aboville Report page.
 */
public function render()
    {
        return view(static::$view);
    }
}
