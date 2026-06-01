<?php

declare(strict_types=1);

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
