<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\AchievementUnlocked;
use App\Events\UserLeveledUp;
use App\Listeners\AchievementUnlockedListener;
use App\Listeners\UserLeveledUpListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Override;

class EventServiceProvider extends ServiceProvider
{
    #[Override]
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
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
