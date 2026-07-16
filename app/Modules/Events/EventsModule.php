<?php

namespace App\Modules\Events;

use App\Modules\BaseModule;
use App\Modules\Events\Services\EventsService;
use App\Modules\Events\Services\TimelineService;
use Artisan;

class EventsModule extends BaseModule
{
    #[\Override]
    protected function onEnable(): void
    {
        // Register events-specific services
        $this->registerEventsServices();
    }

    #[\Override]
    protected function onDisable(): void
    {
        // Clean up events-specific services
        $this->unregisterEventsServices();
    }

    #[\Override]
    protected function onInstall(): void
    {
        // Install events-related database tables
        $this->installEventsTables();
        $this->seedEventsData();
    }

    #[\Override]
    protected function onUninstall(): void
    {
        // Remove events-related data
        $this->removeEventsTables();
    }

    /**
     * Register events-specific services.
     */
    protected function registerEventsServices(): void
    {
        app()->singleton('genealogy.events', fn ($app) => new EventsService);

        app()->singleton('genealogy.events.timeline', fn ($app) => new TimelineService);
    }

    /**
     * Unregister events-specific services.
     */
    protected function unregisterEventsServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install events-related database tables.
     */
    protected function installEventsTables(): void
    {
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Events/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Seed events data.
     */
    protected function seedEventsData(): void
    {
        Artisan::call('db:seed', [
            '--class' => 'App\\Modules\\Events\\Database\\Seeders\\EventsSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Remove events-related tables.
     */
    protected function removeEventsTables(): void
    {
        // Careful implementation needed
    }
}
