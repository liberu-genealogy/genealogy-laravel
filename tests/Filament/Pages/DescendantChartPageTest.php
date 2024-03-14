&lt;?php

namespace Tests\Filament\Pages;

use Tests\TestCase;
use Livewire\Livewire;
use App\Filament\Pages\DescendantChartPage;
use App\Http\Livewire\DescendantChartComponent;

class DescendantChartPageTest extends TestCase
{
    public function testPropertiesAreCorrectlySet()
    {
        $this->assertEquals('filament.pages.descendant-chart', DescendantChartPage::$view);
        $this->assertNull(DescendantChartPage::$resource);
        $this->assertEquals('Descendant Chart', DescendantChartPage::$title);
        $this->assertEquals('heroicon-o-chart-bar', DescendantChartPage::$navigationIcon);
    }

    public function testMountMethodMountsDescendantChartComponent()
    {
        Livewire::test(DescendantChartPage::class)
            ->assertHasNoErrors()
            ->call('mount')
            ->assertMounted(DescendantChartComponent::class);
    }
}
