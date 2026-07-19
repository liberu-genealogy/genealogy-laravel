<?php

namespace App\Modules;

use App\Events\ModuleDisabled;
use App\Events\ModuleEnabled;
use App\Events\ModuleInstalled;
use App\Events\ModuleUninstalled;
use App\Modules\Contracts\ModuleInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use ReflectionClass;

abstract class BaseModule implements ModuleInterface
{
    protected string $name;

    protected string $version;

    protected string $description;

    protected array $dependencies = [];

    protected array $config = [];

    public function __construct()
    {
        $this->loadModuleInfo();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function isEnabled(): bool
    {
        return (bool) Cache::get("module.{$this->name}.enabled", false);
    }

    public function enable(): void
    {
        $ttl = config('modules.cache_ttl', 3600);
        Cache::put("module.{$this->name}.enabled", true, $ttl);
        $this->onEnable();
        Event::dispatch(new ModuleEnabled($this));
    }

    public function disable(): void
    {
        $ttl = config('modules.cache_ttl', 3600);
        Cache::put("module.{$this->name}.enabled", false, $ttl);
        $this->onDisable();
        Event::dispatch(new ModuleDisabled($this));
    }

    public function install(): void
    {
        $this->runMigrations();
        $this->publishAssets();
        $this->onInstall();
        $this->enable();
        Event::dispatch(new ModuleInstalled($this));
    }

    /**
     * Uninstalling marks the module uninstalled and removes its assets. It
     * deliberately does NOT touch the module's data.
     *
     * This used to call a rollbackMigrations() whose body was empty, so an
     * administrator saw a success result and a ModuleUninstalled event while
     * every table and row stayed exactly where it was. Accidentally safe, and
     * an untruth: the signature advertised a rollback that never happened.
     *
     * Keeping the data is the decision, not an oversight — this is genealogical
     * data, and losing it to a mis-clicked uninstall is unrecoverable. Dropping
     * a module's tables is a separate, explicitly confirmed action.
     */
    public function uninstall(): void
    {
        $this->disable();
        $this->removeAssets();
        $this->onUninstall();
        Event::dispatch(new ModuleUninstalled($this));
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    protected function loadModuleInfo(): void
    {
        $moduleInfoPath = $this->getModulePath().'/module.json';

        if (File::exists($moduleInfoPath)) {
            $moduleInfo = json_decode(File::get($moduleInfoPath), true) ?? [];

            $this->name = $moduleInfo['name'] ?? class_basename($this);
            $this->version = $moduleInfo['version'] ?? '1.0.0';
            $this->description = $moduleInfo['description'] ?? '';
            $this->dependencies = $moduleInfo['dependencies'] ?? [];
            $this->config = $moduleInfo['config'] ?? [];
        } else {
            $this->name = $this->name ?? class_basename($this);
            $this->version = $this->version ?? '1.0.0';
            $this->description = $this->description ?? '';
        }
    }

    protected function getModulePath(): string
    {
        $reflection = new ReflectionClass($this);

        return dirname($reflection->getFileName());
    }

    protected function runMigrations(): void
    {
        $migrationsPath = $this->getModulePath().'/database/migrations';

        if (File::exists($migrationsPath)) {
            $relative = str_replace(base_path().'/', '', $migrationsPath);
            Artisan::call('migrate', ['--path' => $relative, '--force' => true]);
        }
    }

    protected function publishAssets(): void
    {
        Artisan::call('vendor:publish', [
            '--tag' => strtolower($this->name).'-assets',
            '--force' => true,
        ]);
    }

    protected function removeAssets(): void
    {
        $assetsPath = public_path("modules/{$this->name}");
        if (File::exists($assetsPath)) {
            File::deleteDirectory($assetsPath);
        }
    }

    protected function onEnable(): void {}

    protected function onDisable(): void {}

    protected function onInstall(): void {}

    protected function onUninstall(): void {}
}
