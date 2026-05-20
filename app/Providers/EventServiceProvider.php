<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\UserCreated;
use App\Listeners\AssignDefaultRole;
use App\Listeners\CreatePersonalTeam;
use App\Events\AchievementUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Events\UserLeveledUp;
use App\Listeners\UserLeveledUpListener;
use Override;
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
        AchievementUnlocked::class => [
            AchievementUnlockedListener::class,
        ],
        UserLeveledUp::class => [
            UserLeveledUpListener::class,
        ],
    ];

    #[Override]
    public function boot(): void
    {
        //
    }

    #[Override]
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
