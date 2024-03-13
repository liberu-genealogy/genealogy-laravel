<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Filament\Widgets\DabovilleReportWidget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DabovilleReportWidgetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_generate_report_with_no_data()
    {
        $widget = new DabovilleReportWidget();
        $result = $widget->generateReport([]);

        $this->assertEmpty($result);
    }

    public function test_generate_report_with_small_dataset()
    {
        $widget = new DabovilleReportWidget();
        $data = factory(\App\Models\ReportData::class, 5)->create()->toArray();
        $result = $widget->generateReport($data);

        $this->assertCount(5, $result);
        $this->assertIsArray($result);
    }

    public function test_generate_report_with_large_dataset()
    {
        $widget = new DabovilleReportWidget();
        $data = factory(\App\Models\ReportData::class, 100)->create()->toArray();
        $result = $widget->generateReport($data);

        $this->assertCount(100, $result);
        $this->assertIsArray($result);
    }

    public function test_calculate_statistics_with_default_parameters()
    {
        $widget = new DabovilleReportWidget();
        $data = factory(\App\Models\ReportData::class, 50)->create()->toArray();
        $result = $widget->calculateStatistics($data);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('average', $result);
        $this->assertArrayHasKey('median', $result);
        $this->assertArrayHasKey('mode', $result);
    }

    public function test_calculate_statistics_with_custom_parameters()
    {
        $widget = new DabovilleReportWidget();
        $data = factory(\App\Models\ReportData::class, 20)->create()->toArray();
        $customParameters = ['calculate' => 'variance'];
        $result = $widget->calculateStatistics($data, $customParameters);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('variance', $result);
    }

    public function test_calculate_statistics_handles_empty_dataset()
    {
        $widget = new DabovilleReportWidget();
        $result = $widget->calculateStatistics([]);

        $this->assertIsArray($result);
        $this->assertTrue(empty($result));
    }
}
