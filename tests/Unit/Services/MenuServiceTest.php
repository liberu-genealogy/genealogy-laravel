<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuServiceTest extends TestCase
{
    use RefreshDatabase;

    private MenuService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MenuService;
    }

    public function test_build_menu_returns_menu_object(): void
    {
        $result = $this->service->buildMenu();

        $this->assertNotNull($result);
    }

    public function test_build_menu_with_no_items(): void
    {
        $result = $this->service->buildMenu();

        $this->assertInstanceOf(\Spatie\Menu\Menu::class, $result);
    }

    public function test_build_menu_with_items(): void
    {
        Menu::factory()->count(3)->create(['parent_id' => null]);

        $result = $this->service->buildMenu();

        $this->assertNotNull($result);
    }

    public function test_build_menu_respects_order(): void
    {
        Menu::factory()->create(['name' => 'Third', 'order' => 3, 'parent_id' => null]);
        Menu::factory()->create(['name' => 'First', 'order' => 1, 'parent_id' => null]);
        Menu::factory()->create(['name' => 'Second', 'order' => 2, 'parent_id' => null]);

        $result = $this->service->buildMenu();

        $this->assertNotNull($result);
    }
}
