<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Filament\Widgets\DescendantChartWidget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DescendantChartWidgetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_render_with_no_data()
    {
        $widget = new DescendantChartWidget();
        $view = $widget->render();

        $this->assertEmpty($view->getData());
    }

    public function test_render_with_data()
    {
        $widget = new DescendantChartWidget();
        $data = ['descendants' => $this->faker->words(5)];
        $widget->loadData($data);
        $view = $widget->render();

        $this->assertEquals($data, $view->getData()['descendants']);
    }

    public function test_load_data_with_empty_array()
    {
        $widget = new DescendantChartWidget();
        $widget->loadData([]);
        $view = $widget->render();

        $this->assertEmpty($view->getData());
    }

    public function test_load_data_with_valid_data()
    {
        $widget = new DescendantChartWidget();
        $data = ['descendants' => $this->faker->words(5)];
        $widget->loadData($data);
        $view = $widget->render();

        $this->assertEquals($data, $view->getData()['descendants']);
    }

    public function test_load_data_with_invalid_data_structure()
    {
        $widget = new DescendantChartWidget();
        $data = $this->faker->words(5); // Incorrect structure, expecting associative array
        $widget->loadData(['descendants' => $data]);
        $view = $widget->render();

        $this->assertIsArray($view->getData()['descendants']);
        $this->assertEquals($data, $view->getData()['descendants']);
    }
}
