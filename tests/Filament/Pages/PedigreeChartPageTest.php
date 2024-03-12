&lt;?php

namespace Tests\Filament\Pages;

use Tests\TestCase;
use App\Filament\Pages\PedigreeChartPage;

class PedigreeChartPageTest extends TestCase
{
    public function testPropertiesAreCorrectlySet()
    {
        $this->assertEquals('filament.pages.pedigree-chart', PedigreeChartPage::$view);
        $this->assertNull(PedigreeChartPage::$resource);
        $this->assertEquals('Pedigree Chart', PedigreeChartPage::$title);
        $this->assertEquals('heroicon-o-chart-bar', PedigreeChartPage::$navigationIcon);
    }
}
