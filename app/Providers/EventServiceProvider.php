<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\UserCreated::class => [
            \App\Listeners\AssignDefaultRole::class,
            \App\Listeners\CreatePersonalTeam::class,
        ],
    ];

    #[\Override]
    public function boot(): void
    {
        //
    }

    #[\Override]
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
