<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Listeners\AssignDefaultRole;
use App\Listeners\CreatePersonalTeam;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserCreated::class => [
            AssignDefaultRole::class,
            CreatePersonalTeam::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}