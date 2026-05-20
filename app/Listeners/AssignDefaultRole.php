<?php

namespace App\Listeners;

use App\Events\UserCreated;

class AssignDefaultRole
{
    public function handle(UserCreated $event): void
    {
        // Assign default role if roles exist
        if (method_exists($event->user, 'assignRole')) {
            try {
                $event->user->assignRole('user');
            } catch (\Throwable $e) {
                // Role may not exist yet - silently skip
            }
        }
    }
}
