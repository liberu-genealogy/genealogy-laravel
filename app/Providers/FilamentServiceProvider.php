<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Livewire components in App\Livewire are auto-discovered by Livewire 3+.
        // No manual registration needed.
    }
}
