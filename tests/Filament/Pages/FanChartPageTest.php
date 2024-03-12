&lt;?php

namespace Tests\Filament\Pages;

use Tests\TestCase;
use Livewire\Livewire;
use App\Filament\Pages\FanChartPage;
use App\Http\Livewire\FanChart;

class FanChartPageTest extends TestCase
{
    public function testPropertiesAreCorrectlySet()
    {
        $this->assertEquals('livewire.fan-chart', FanChartPage::$view);
        $this->assertNull(FanChartPage::$resource);
        $this->assertEquals('Fan Chart', FanChartPage::$title);
        $this->assertEquals('heroicon-o-chart-pie', FanChartPage::$navigationIcon);
    }

    public function testMountMethodMountsFanChartComponent()
    {
        Livewire::test(FanChartPage::class)
            ->assertHasNoErrors()
            ->call('mount')
            ->assertMounted(FanChart::class);
    }
}
