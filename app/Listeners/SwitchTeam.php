<?php

namespace App\Listeners;

use Filament\Events\TenantSet;

class SwitchTeam
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantSet $event): void
    {
        $user = auth()->user();

        if ($user && $user->hasTeam($event->tenant)) {
            $user->switchTeam($event->tenant);
        }
    }
}
