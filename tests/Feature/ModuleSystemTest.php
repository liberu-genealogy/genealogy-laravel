<?php

namespace Tests\Feature;

use App\Modules\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleSystemTest extends TestCase
{
    use RefreshDatabase;

    protected ModuleManager $moduleManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleManager = app(ModuleManager::class);
    }

    public function test_it_can_list_all_modules(): void
    {
        $modules = $this->moduleManager->all();
        $this->assertNotEmpty($modules);
    }

    public function test_it_can_get_module_by_name(): void
    {
        $module = $this->moduleManager->get('Core');
        $this->assertNotNull($module);
        $this->assertEquals('Core', $module->getName());
    }

    public function test_it_can_enable_and_disable_modules(): void
    {
        // First enable Core since other modules depend on it
        $coreModule = $this->moduleManager->get('Core');
        $this->assertNotNull($coreModule, 'Core module should exist');
        $coreModule->enable();

        // Now enable Media (depends only on Core)
        $moduleName = 'Media';
        $result = $this->moduleManager->enable($moduleName);
        $this->assertTrue($result);

        $module = $this->moduleManager->get($moduleName);
        $this->assertTrue($module->isEnabled());

        // Disable Media module
        $result = $this->moduleManager->disable($moduleName);
        $this->assertTrue($result);

        $module = $this->moduleManager->get($moduleName);
        $this->assertFalse($module->isEnabled());
    }

    public function test_it_can_get_module_info(): void
    {
        $info = $this->moduleManager->getModuleInfo('Core');

        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('version', $info);
        $this->assertArrayHasKey('description', $info);
        $this->assertEquals('Core', $info['name']);
    }

    public function test_it_returns_false_for_non_existent_modules(): void
    {
        $result = $this->moduleManager->enable('NonExistentModule');
        $this->assertFalse($result);

        $result = $this->moduleManager->disable('NonExistentModule');
        $this->assertFalse($result);

        $module = $this->moduleManager->get('NonExistentModule');
        $this->assertNull($module);
    }
}