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

    /** @test */
    public function it_can_list_all_modules()
    {
        $modules = $this->moduleManager->all();
        $this->assertNotEmpty($modules);
    }

    /** @test */
    public function it_can_get_module_by_name()
    {
        $module = $this->moduleManager->get('Core');
        $this->assertNotNull($module);
        $this->assertEquals('Core', $module->getName());
    }

    /** @test */
    public function it_can_enable_and_disable_modules()
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

    /** @test */
    public function it_can_get_module_info()
    {
        $info = $this->moduleManager->getModuleInfo('Core');

        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('version', $info);
        $this->assertArrayHasKey('description', $info);
        $this->assertEquals('Core', $info['name']);
    }

    /** @test */
    public function it_returns_false_for_non_existent_modules()
    {
        $result = $this->moduleManager->enable('NonExistentModule');
        $this->assertFalse($result);

        $result = $this->moduleManager->disable('NonExistentModule');
        $this->assertFalse($result);

        $module = $this->moduleManager->get('NonExistentModule');
        $this->assertNull($module);
    }
}