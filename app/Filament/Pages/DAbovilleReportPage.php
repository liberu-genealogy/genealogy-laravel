<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomFilamentBasePage;
use Livewire\Livewire;

class DAbovilleReportPage extends CustomFilamentBasePage
{
    protected static string $view = 'livewire.daboville-report';
    protected static ?string $title = 'DAboville Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    public function getTitle(): string
    {
        return static::$title;
    }

    public static function getNavigationIcon(): string
    {
        return static::$navigationIcon;
    }

//    public function render(): \Illuminate\Contracts\Support\Renderable
//    {
//        return \Livewire::mount(static::$view);
//    }

    public function mount(): void
    {
        Livewire::mount(static::$view);
    }
}
/**
 * Defines the DAboville Report page in the Filament admin panel.
 */
        Livewire::mount(static::$view);
    }
}
        Livewire::mount(static::$view);
    }
}
