<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DAbovilleReportPage extends Page
{
    protected static string $view = 'livewire.daboville-report';

    public function render()
    {
        return view(static::$view);
    }
}
