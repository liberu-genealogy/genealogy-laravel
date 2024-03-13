<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Filament\Widgets\FanChartWidget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class FanChartWidgetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_render_with_no_data()
    {
        $widget = new FanChartWidget();
        $view = $widget->render();

        $this->assertEmpty($view->getData());
    }

    public function test_render_with_data()
    {
        $widget = new FanChartWidget();
        $data = ['ancestors' => $this->faker->words(5)];
        $widget->loadData($data);
        $view = $widget->render();

        $this->assertEquals($data, $view->getData()['ancestors']);
    }

    public function test_load_data_with_empty_array()
    {
        $widget = new FanChartWidget();
        $widget->loadData([]);
        $view = $widget->render();

        $this->assertEmpty($view->getData());
    }

    public function test_load_data_with_valid_data()
    {
        $widget = new FanChartWidget();
        $data = ['ancestors' => $this->faker->words(5)];
        $widget->loadData($data);
        $view = $widget->render();

        $this->assertEquals($data, $view->getData()['ancestors']);
    }

    public function test_load_data_with_invalid_data_structure()
    {
        $widget = new FanChartWidget();
        $data = $this->faker->words(5); // Incorrect structure, expecting associative array
        $widget->loadData(['ancestors' => $data]);
        $view = $widget->render();

        $this->assertIsArray($view->getData()['ancestors']);
        $this->assertEquals($data, $view->getData()['ancestors']);
    }
}
