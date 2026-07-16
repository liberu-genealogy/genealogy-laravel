<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FanChartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_fan_chart_requires_authentication(): void
    {
        $this->get('/fan-chart')->assertRedirect();
    }

    public function test_fan_chart_route_is_registered(): void
    {
        $this->assertNotNull(app('router')->getRoutes()->getByName('fan-chart'));
    }

    public function test_pedigree_chart_route_is_registered(): void
    {
        $this->assertNotNull(app('router')->getRoutes()->getByName('pedigree-chart'));
    }

    public function test_family_tree_route_is_registered(): void
    {
        $this->assertNotNull(app('router')->getRoutes()->getByName('family-tree'));
    }
}
