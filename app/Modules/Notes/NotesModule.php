<?php

namespace App\Modules\Notes;

use App\Modules\Notes\Services\NotesService;
use Artisan;
use App\Modules\BaseModule;

class NotesModule extends BaseModule
{
    protected function onEnable(): void
    {
        // Register notes-specific services
        $this->registerNotesServices();
    }

    protected function onDisable(): void
    {
        // Clean up notes-specific services
        $this->unregisterNotesServices();
    }

    protected function onInstall(): void
    {
        // Install notes-related database tables
        $this->installNotesTables();
    }

    protected function onUninstall(): void
    {
        // Remove notes-related data
        $this->removeNotesTables();
    }

    /**
     * Register notes-specific services.
     */
    protected function registerNotesServices(): void
    {
        app()->singleton('genealogy.notes', function ($app) {
            return new NotesService();
        });
    }

    /**
     * Unregister notes-specific services.
     */
    protected function unregisterNotesServices(): void
    {
        // Clean up registered services
    }

    /**
     * Install notes-related database tables.
     */
    protected function installNotesTables(): void
    {
        Artisan::call('migrate', [
            '--path' => 'app/Modules/Notes/database/migrations',
            '--force' => true,
        ]);
    }

    /**
     * Remove notes-related tables.
     */
    protected function removeNotesTables(): void
    {
        // Careful implementation needed
    }
}