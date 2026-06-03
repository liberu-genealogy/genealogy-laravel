<?php

namespace App\Modules;

use App\Modules\Contracts\ModuleInterface;
use App\Modules\Support\ExternalModuleLoader;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ModuleManager
{
    protected Collection $modules;

    public function __construct()
    {
        $this->modules = collect();
        $this->loadModules();
    }

    public function all(): Collection
    {
        return $this->modules;
    }

    public function enabled(): Collection
    {
        return $this->modules->filter(fn ($module) => $module->isEnabled());
    }

    public function disabled(): Collection
    {
        return $this->modules->filter(fn ($module): bool => ! $module->isEnabled());
    }

    public function get(string $name): ?ModuleInterface
    {
        return $this->modules->first(fn ($module): bool => $module->getName() === $name);
    }

    public function has(string $name): bool
    {
        return $this->modules->contains(fn ($module): bool => $module->getName() === $name);
    }

    public function enable(string $name): bool
    {
        $module = $this->get($name);

        if (! $module instanceof ModuleInterface) {
            return false;
        }

        if (! $this->checkDependencies($module)) {
            throw new Exception("Module {$name} has unmet dependencies.");
        }

        $module->enable();
        $this->invalidateCache();

        return true;
    }

    public function disable(string $name): bool
    {
        $module = $this->get($name);

        if (! $module instanceof ModuleInterface) {
            return false;
        }

        if ($this->hasDependents($name)) {
            throw new Exception("Cannot disable module {$name} as other modules depend on it.");
        }

        $module->disable();
        $this->invalidateCache();

        return true;
    }

    public function install(string $name): bool
    {
        $module = $this->get($name);

        if (! $module instanceof ModuleInterface) {
            return false;
        }

        if (! $this->checkDependencies($module)) {
            throw new Exception("Module {$name} has unmet dependencies.");
        }

        $module->install();
        $this->invalidateCache();

        return true;
    }

    public function uninstall(string $name): bool
    {
        $module = $this->get($name);

        if (! $module instanceof ModuleInterface) {
            return false;
        }

        if ($this->hasDependents($name)) {
            throw new Exception("Cannot uninstall module {$name} as other modules depend on it.");
        }

        $module->uninstall();
        $this->invalidateCache();

        return true;
    }

    public function register(ModuleInterface $module): void
    {
        $this->modules->put($module->getName(), $module);
    }

    public function getModuleInfo(string $name): array
    {
        $module = $this->get($name);

        if (! $module instanceof ModuleInterface) {
            return [];
        }

        return [
            'name'         => $module->getName(),
            'version'      => $module->getVersion(),
            'description'  => $module->getDescription(),
            'dependencies' => $module->getDependencies(),
            'enabled'      => $module->isEnabled(),
            'config'       => $module->getConfig(),
        ];
    }

    public function getAllModulesInfo(): array
    {
        return $this->modules->map(fn ($module) => $this->getModuleInfo($module->getName()))->toArray();
    }

    public function checkHealth(): array
    {
        $report = [];

        foreach ($this->modules as $name => $module) {
            $report[$name] = [
                'enabled'      => $module->isEnabled(),
                'version'      => $module->getVersion(),
                'dependencies' => $module->getDependencies(),
                'deps_met'     => $this->checkDependencies($module),
            ];
        }

        return $report;
    }

    protected function loadModules(): void
    {
        $searchPaths = $this->discoverPaths();

        foreach ($searchPaths as $basePath => $namespace) {
            if (! File::exists($basePath)) {
                continue;
            }

            foreach (File::directories($basePath) as $modulePath) {
                $moduleName = basename((string) $modulePath);
                $this->loadModule($moduleName, $modulePath, $namespace);
            }
        }

        if (config('modules.load_composer_modules', false)) {
            $this->loadComposerModules();
        }
    }

    protected function loadComposerModules(): void
    {
        $loader  = new ExternalModuleLoader();
        $modules = $loader->discoverFromVendor(base_path('vendor'));

        foreach ($modules as $module) {
            if (! $this->has($module->getName())) {
                $this->register($module);
            }
        }
    }

    protected function discoverPaths(): array
    {
        $paths = [
            app_path('Modules') => 'App\\Modules',
        ];

        // Support boilerplate-style app-modules/ directory at project root
        $externalPath = base_path('app-modules');
        if (File::exists($externalPath)) {
            $paths[$externalPath] = 'Modules';
        }

        // Additional paths from config
        foreach (config('modules.external_paths', []) as $path => $namespace) {
            $paths[$path] = $namespace;
        }

        return $paths;
    }

    protected function loadModule(string $moduleName, string $modulePath, string $baseNamespace): void
    {
        $moduleClass = "{$baseNamespace}\\{$moduleName}\\{$moduleName}Module";

        if (class_exists($moduleClass)) {
            $module = new $moduleClass();
            if ($module instanceof ModuleInterface) {
                $this->register($module);
            }
        }
    }

    protected function checkDependencies(ModuleInterface $module): bool
    {
        foreach ($module->getDependencies() as $dependency) {
            $dep = $this->get($dependency);
            if (! $dep || ! $dep->isEnabled()) {
                return false;
            }
        }

        return true;
    }

    protected function hasDependents(string $moduleName): bool
    {
        return $this->enabled()->contains(fn ($module) => in_array($moduleName, $module->getDependencies()));
    }

    protected function invalidateCache(): void
    {
        Cache::forget(config('modules.cache_key', 'modules'));
    }
}
