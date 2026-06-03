<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Module Discovery Path
    |--------------------------------------------------------------------------
    |
    | This is the path where modules will be discovered and loaded from.
    | The system will scan this directory for module directories.
    |
    */
    'path' => app_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Module Caching
    |--------------------------------------------------------------------------
    |
    | Enable or disable module information caching. When enabled, module
    | information will be cached to improve performance in production.
    |
    */
    'cache' => env('MODULES_CACHE', true),

    /*
    |--------------------------------------------------------------------------
    | Cache Key
    |--------------------------------------------------------------------------
    |
    | The cache key used to store module information.
    |
    */
    'cache_key' => 'modules',

    /*
    |--------------------------------------------------------------------------
    | Cache TTL
    |--------------------------------------------------------------------------
    |
    | The time-to-live for cached module information in seconds.
    |
    */
    'cache_ttl' => 3600,

    /*
    |--------------------------------------------------------------------------
    | Auto Discovery
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic module discovery. When enabled, the system
    | will automatically discover and register modules found in the modules path.
    |
    */
    'auto_discovery' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Enabled Modules
    |--------------------------------------------------------------------------
    |
    | List of modules that should be enabled by default when first discovered.
    |
    */
    'default_enabled' => [
        'Core',
        'Person',
        'Family',
        'Tree',
        'Places',
        'Sources',
        'Media',
        'Events',
        'Notes',
        'DNA',
        'Import',
        'Admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | The base namespace for modules.
    |
    */
    'namespace' => 'App\\Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Assets Path
    |--------------------------------------------------------------------------
    |
    | The public path where module assets will be published.
    |
    */
    'assets_path' => 'modules',

    /*
    |--------------------------------------------------------------------------
    | Module Commands
    |--------------------------------------------------------------------------
    |
    | Enable or disable module management commands.
    |
    */
    'commands' => true,

    /*
    |--------------------------------------------------------------------------
    | Module Migration Path
    |--------------------------------------------------------------------------
    |
    | The path within each module where migrations are stored.
    |
    */
    'migration_path' => 'database/migrations',

    /*
    |--------------------------------------------------------------------------
    | Module Seeder Path
    |--------------------------------------------------------------------------
    |
    | The path within each module where seeders are stored.
    |
    */
    'seeder_path' => 'database/seeders',

    /*
    |--------------------------------------------------------------------------
    | Module Config Path
    |--------------------------------------------------------------------------
    |
    | The path within each module where configuration files are stored.
    |
    */
    'config_path' => 'config',

    /*
    |--------------------------------------------------------------------------
    | Module View Path
    |--------------------------------------------------------------------------
    |
    | The path within each module where view files are stored.
    |
    */
    'view_path' => 'resources/views',

    /*
    |--------------------------------------------------------------------------
    | Module Language Path
    |--------------------------------------------------------------------------
    |
    | The path within each module where language files are stored.
    |
    */
    'lang_path' => 'resources/lang',

    /*
    |--------------------------------------------------------------------------
    | Module Route Path
    |--------------------------------------------------------------------------
    |
    | The path within each module where route files are stored.
    |
    */
    'route_path' => 'routes',

    /*
    |--------------------------------------------------------------------------
    | External Module Paths
    |--------------------------------------------------------------------------
    |
    | Additional directories to scan for modules, keyed by absolute path with
    | the base PSR-4 namespace as value. The app-modules/ root directory is
    | always scanned automatically; add further paths here if needed.
    |
    | Example:
    |   base_path('custom-modules') => 'Custom\\Modules',
    |
    */
    'external_paths' => [],

    /*
    |--------------------------------------------------------------------------
    | Load Composer Modules
    |--------------------------------------------------------------------------
    |
    | When enabled, modules installed as Composer packages (vendor) will also
    | be discovered if they expose a module class.
    |
    */
    'load_composer_modules' => false,
];
