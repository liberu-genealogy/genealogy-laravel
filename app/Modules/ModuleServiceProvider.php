<?php

namespace App\Modules;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ModuleServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->registerFromPath(app_path('Modules'), 'App\\Modules');

        if (File::exists(base_path('app-modules'))) {
            $this->registerFromPath(base_path('app-modules'), 'Modules');
        }

        foreach (config('modules.external_paths', []) as $path => $namespace) {
            if (File::exists($path)) {
                $this->registerFromPath($path, $namespace);
            }
        }
    }

    public function boot(): void
    {
        $this->bootFromPath(app_path('Modules'), 'App\\Modules');

        if (File::exists(base_path('app-modules'))) {
            $this->bootFromPath(base_path('app-modules'), 'Modules');
        }

        foreach (config('modules.external_paths', []) as $path => $namespace) {
            if (File::exists($path)) {
                $this->bootFromPath($path, $namespace);
            }
        }
    }

    protected function registerFromPath(string $basePath, string $namespace): void
    {
        if (! File::exists($basePath)) {
            return;
        }

        foreach (File::directories($basePath) as $modulePath) {
            $moduleName = basename((string) $modulePath);
            $this->registerModule($moduleName, $modulePath, $namespace);
        }
    }

    /**
     * Register phase: service providers, config merges, and migrations only.
     * Routes/views/translations are deferred to boot() where cache is available.
     */
    protected function registerModule(string $moduleName, string $modulePath, string $namespace): void
    {
        $providerClass = "{$namespace}\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";
        if (class_exists($providerClass)) {
            $this->app->register($providerClass);
        }

        $configPath = $modulePath.'/config';
        if (File::exists($configPath)) {
            foreach (File::files($configPath) as $configFile) {
                $key = Str::snake($moduleName).'.'.$configFile->getFilenameWithoutExtension();
                $this->mergeConfigFrom($configFile->getPathname(), $key);
            }
        }

        // Migrations are always registered so `artisan migrate` works unconditionally.
        $this->loadMigrationsFrom($modulePath.'/database/migrations');
    }

    protected function registerModuleRoutes(string $moduleName, string $modulePath): void
    {
        $routesPath = $modulePath.'/routes';

        if (! File::exists($routesPath)) {
            return;
        }

        foreach (['web.php', 'api.php', 'admin.php'] as $routeFile) {
            $fullPath = $routesPath.'/'.$routeFile;
            if (File::exists($fullPath)) {
                $this->loadRoutesFrom($fullPath);
            }
        }
    }

    protected function bootFromPath(string $basePath, string $namespace): void
    {
        if (! File::exists($basePath)) {
            return;
        }

        foreach (File::directories($basePath) as $modulePath) {
            $moduleName = basename((string) $modulePath);
            $this->bootModule($moduleName, $modulePath);
        }
    }

    protected function bootModule(string $moduleName, string $modulePath): void
    {
        $assetsPath = $modulePath.'/resources/assets';
        if (File::exists($assetsPath)) {
            $this->publishes([
                $assetsPath => public_path("modules/{$moduleName}"),
            ], Str::snake($moduleName).'-assets');
        }

        $configPath = $modulePath.'/config';
        if (File::exists($configPath)) {
            foreach (File::files($configPath) as $configFile) {
                $this->publishes([
                    $configFile->getPathname() => config_path(Str::snake($moduleName).'.'.$configFile->getFilename()),
                ], Str::snake($moduleName).'-config');
            }
        }

        // Routes, views, and translations are gated behind enabled state.
        // Cache is available during boot() so this is safe.
        if (! $this->isModuleEnabled($moduleName)) {
            return;
        }

        $this->registerModuleRoutes($moduleName, $modulePath);

        $viewsPath = $modulePath.'/resources/views';
        if (File::exists($viewsPath)) {
            $this->loadViewsFrom($viewsPath, Str::snake($moduleName));
        }

        $langPath = $modulePath.'/resources/lang';
        if (File::exists($langPath)) {
            $this->loadTranslationsFrom($langPath, Str::snake($moduleName));
        }
    }

    /**
     * Checks cache first (runtime enabled/disabled state), then falls back to
     * the default_enabled config list (module is enabled by default if listed).
     */
    protected function isModuleEnabled(string $moduleName): bool
    {
        $cacheKey = "module.{$moduleName}.enabled";

        try {
            if (Cache::has($cacheKey)) {
                return (bool) Cache::get($cacheKey);
            }
        } catch (\Throwable) {
            // Cache may not be available in all environments (e.g. during tests).
        }

        return in_array($moduleName, config('modules.default_enabled', []), true);
    }
}
