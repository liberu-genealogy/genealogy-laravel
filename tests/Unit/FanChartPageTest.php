<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Filament\Pages\FanChartPage;

class FanChartPageTest extends TestCase
{
    public function testGetNavigationIconReturnsCorrectIcon(): void
    {
        $fanChartPage = new FanChartPage();
        $fanChartPage->navigationIcon = 'test-icon';

        $this->assertEquals('test-icon', $fanChartPage->getNavigationIcon());
    }
}
