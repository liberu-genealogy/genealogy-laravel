<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomFilamentBasePage;
use Livewire\Livewire;

class DAbovilleReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.daboville-report';

    /**
     * Renders the Livewire component.
     * 
     * This function mounts the Livewire component specified by the static view variable
     * and returns it for rendering.
     */
    public function render(): \Illuminate\Contracts\Support\Renderable
    {
        return \Livewire::mount(static::$view);
    }
//        return \Livewire::mount(static::$view);
//    }

    /**
     * Mounts the Livewire component by setting the view.
     */
    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
}
        Livewire::mount(static::$view);
    }
}
