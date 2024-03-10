<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PedigreeChart;

class PedigreeChartTest extends TestCase
{
    use RefreshDatabase;

    public function testPedigreeChartRoute()
    {
        // Define the expected route name
        $expectedRouteName = 'pedigree-chart';

        // Get the actual route name from the route collection
        $actualRouteName = Route::getRoutes()->getByName($expectedRouteName)->getName();

        // Assert that the actual route name matches the expected route name
        $this->assertEquals($expectedRouteName, $actualRouteName);

        // Get the actual route action from the route collection
        $actualRouteAction = Route::getRoutes()->getByName($expectedRouteName)->getAction();

        // Assert that the actual route action is an instance of PedigreeChart class
        $this->assertInstanceOf(PedigreeChart::class, $actualRouteAction['uses']);
    }
}
